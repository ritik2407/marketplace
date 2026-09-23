<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminListingController extends Controller
{
    /**
     * Display a listing of all ads for administrative management.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category_id', 'city_id', 'status', 'type', 'is_featured']);

        $query = Listing::with(['user', 'category', 'city', 'images'])->latest();

        if (! empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if ($request->has('is_featured') && $request->is_featured !== null && $request->is_featured !== '') {
            $query->where('is_featured', (bool) $request->is_featured);
        }

        $listings = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.listings.index', compact('listings', 'categories', 'cities', 'filters'));
    }

    /**
     * Show the edit form for any listing as admin.
     */
    public function edit(int $id): View
    {
        $listing = Listing::with(['category', 'subcategory', 'state', 'city', 'area', 'images', 'user'])->findOrFail($id);
        $categories = Category::with('subcategories')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.listings.edit', compact('listing', 'categories', 'cities'));
    }

    /**
     * Update any listing as admin.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'type' => ['required', 'in:product,service'],
            'status' => ['required', 'in:active,sold,inactive'],
            'is_featured' => ['nullable', 'boolean'],
            'is_negotiable' => ['nullable', 'boolean'],
            'condition' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $listing->update([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'is_negotiable' => $request->boolean('is_negotiable'),
            'condition' => $validated['condition'] ?? null,
            'description' => $validated['description'],
        ]);

        // Process any uploaded images from the main form
        if ($request->hasFile('images')) {
            $hasExistingPrimary = $listing->images()->where('is_primary', true)->exists();
            $existingCount = $listing->images()->count();

            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => (! $hasExistingPrimary && $index === 0),
                    'order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.listings.edit', $listing->id)->with('success', "Listing '{$listing->title}' updated successfully.");
    }

    /**
     * Upload additional images for a listing.
     */
    public function uploadImages(Request $request, int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);

        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $hasExistingPrimary = $listing->images()->where('is_primary', true)->exists();
        $existingCount = $listing->images()->count();

        foreach ($request->file('images') as $index => $imageFile) {
            $path = $imageFile->store('listings', 'public');
            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
                'is_primary' => (! $hasExistingPrimary && $index === 0),
                'order' => $existingCount + $index,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    /**
     * Set an image as the primary cover photo.
     */
    public function setPrimaryImage(int $id): RedirectResponse
    {
        $image = ListingImage::findOrFail($id);

        // Reset all images for this listing
        ListingImage::where('listing_id', $image->listing_id)->update(['is_primary' => false]);

        // Set the selected one as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary cover image updated.');
    }

    /**
     * Delete an individual listing image.
     */
    public function deleteImage(int $id): RedirectResponse
    {
        $image = ListingImage::findOrFail($id);
        $listingId = $image->listing_id;
        $wasPrimary = $image->is_primary;

        // Delete physical file if local
        if (! str_starts_with($image->image_path, 'http')) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        // If the deleted image was primary, make the first remaining image primary
        if ($wasPrimary) {
            $nextImage = ListingImage::where('listing_id', $listingId)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Toggle featured status of a listing.
     */
    public function toggleFeatured(int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);
        $listing->update(['is_featured' => ! $listing->is_featured]);

        $state = $listing->is_featured ? 'marked as Featured ★' : 'removed from Featured';

        return back()->with('success', "Listing {$state}.");
    }

    /**
     * Toggle status (active/sold/inactive).
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);
        $status = $request->input('status', 'active');

        if (in_array($status, ['active', 'sold', 'inactive'])) {
            $listing->update(['status' => $status]);
        }

        return back()->with('success', "Status for listing #{$listing->id} set to '{$status}'.");
    }

    /**
     * Delete any listing as admin.
     */
    public function destroy(int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);

        foreach ($listing->images as $img) {
            if (! str_starts_with($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $listing->delete();

        return redirect()->route('admin.listings.index')->with('success', 'Listing deleted successfully from the marketplace.');
    }
}
