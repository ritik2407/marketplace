<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\State;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ListingController extends Controller
{
    /**
     * Display a listing of products/services with advanced filters.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'type',
            'category',
            'category_id',
            'subcategory',
            'subcategory_id',
            'city',
            'city_id',
            'state_id',
            'area_id',
            'min_price',
            'max_price',
            'condition',
            'is_negotiable',
            'is_featured',
            'with_photos',
            'posted_within',
            'sort',
        ]);

        $query = Listing::active()
            ->filter($filters)
            ->with(['category', 'subcategory', 'city', 'area', 'state', 'images', 'user']);

        $listings = $query->paginate(12)->withQueryString();

        $categories = Category::with('subcategories')->orderBy('order')->get();
        $popularCities = City::where('is_popular', true)->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $selectedCategory = ! empty($filters['category']) ? Category::where('slug', $filters['category'])->first() : null;
        if (! $selectedCategory && ! empty($filters['category_id']) && ! is_array($filters['category_id'])) {
            $selectedCategory = Category::find($filters['category_id']);
        }

        $selectedSubcategory = ! empty($filters['subcategory']) ? Subcategory::where('slug', $filters['subcategory'])->first() : null;
        if (! $selectedSubcategory && ! empty($filters['subcategory_id']) && ! is_array($filters['subcategory_id'])) {
            $selectedSubcategory = Subcategory::find($filters['subcategory_id']);
        }

        $selectedCity = ! empty($filters['city']) ? City::where('slug', $filters['city'])->first() : null;
        if (! $selectedCity && ! empty($filters['city_id']) && ! is_array($filters['city_id'])) {
            $selectedCity = City::find($filters['city_id']);
        }

        $selectedState = ! empty($filters['state_id']) && ! is_array($filters['state_id']) ? State::find($filters['state_id']) : ($selectedCity ? $selectedCity->state : null);

        $cities = $selectedState ? City::where('state_id', $selectedState->id)->orderBy('name')->get() : $popularCities;
        $areas = $selectedCity ? Area::where('city_id', $selectedCity->id)->orderBy('name')->get() : collect();

        $pageTitle = 'All Marketplace Listings';
        if ($selectedCity && $selectedCategory) {
            $pageTitle = "{$selectedCategory->name} in {$selectedCity->name}";
        } elseif ($selectedCategory) {
            $pageTitle = "{$selectedCategory->name} Classifieds";
        } elseif ($selectedCity) {
            $pageTitle = "Listings in {$selectedCity->name}";
        }

        $activeFilterPills = $this->buildActiveFilterPills($filters, $selectedCategory, $selectedSubcategory, $selectedCity, $selectedState);

        return view('listings.index', compact(
            'listings',
            'categories',
            'popularCities',
            'states',
            'cities',
            'areas',
            'filters',
            'selectedCategory',
            'selectedSubcategory',
            'selectedCity',
            'selectedState',
            'activeFilterPills',
            'pageTitle'
        ));
    }

    /**
     * Category / Subcategory dedicated page.
     */
    public function byCategory(Request $request, string $category_slug, ?string $subcategory_slug = null): View
    {
        $category = Category::where('slug', $category_slug)->firstOrFail();
        $subcategory = $subcategory_slug ? Subcategory::where('slug', $subcategory_slug)->where('category_id', $category->id)->firstOrFail() : null;

        $filters = array_merge($request->all(), [
            'category' => $category->slug,
            'subcategory' => $subcategory?->slug,
        ]);

        $query = Listing::active()
            ->filter($filters)
            ->with(['category', 'subcategory', 'city', 'area', 'state', 'images', 'user']);

        $listings = $query->paginate(12)->withQueryString();

        $categories = Category::with('subcategories')->orderBy('order')->get();
        $popularCities = City::where('is_popular', true)->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $selectedCategory = $category;
        $selectedSubcategory = $subcategory;
        $selectedCity = ! empty($filters['city']) ? City::where('slug', $filters['city'])->first() : null;
        $selectedState = ! empty($filters['state_id']) && ! is_array($filters['state_id']) ? State::find($filters['state_id']) : ($selectedCity ? $selectedCity->state : null);

        $cities = $selectedState ? City::where('state_id', $selectedState->id)->orderBy('name')->get() : $popularCities;
        $areas = $selectedCity ? Area::where('city_id', $selectedCity->id)->orderBy('name')->get() : collect();

        $pageTitle = $subcategory ? "{$subcategory->name} in {$category->name}" : "{$category->name} Ads";

        $activeFilterPills = $this->buildActiveFilterPills($filters, $selectedCategory, $selectedSubcategory, $selectedCity, $selectedState);

        return view('listings.index', compact(
            'listings',
            'categories',
            'popularCities',
            'states',
            'cities',
            'areas',
            'filters',
            'selectedCategory',
            'selectedSubcategory',
            'selectedCity',
            'selectedState',
            'activeFilterPills',
            'pageTitle'
        ));
    }

    /**
     * City dedicated page.
     */
    public function byCity(Request $request, string $city_slug): View
    {
        $city = City::with('state')->where('slug', $city_slug)->firstOrFail();

        $filters = array_merge($request->all(), [
            'city' => $city->slug,
        ]);

        $query = Listing::active()
            ->filter($filters)
            ->with(['category', 'subcategory', 'city', 'area', 'state', 'images', 'user']);

        $listings = $query->paginate(12)->withQueryString();

        $categories = Category::with('subcategories')->orderBy('order')->get();
        $popularCities = City::where('is_popular', true)->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $selectedCategory = ! empty($filters['category']) ? Category::where('slug', $filters['category'])->first() : null;
        $selectedSubcategory = ! empty($filters['subcategory']) && $selectedCategory ? Subcategory::where('slug', $filters['subcategory'])->where('category_id', $selectedCategory->id)->first() : null;
        $selectedCity = $city;
        $selectedState = $city->state;

        $cities = $selectedState ? City::where('state_id', $selectedState->id)->orderBy('name')->get() : $popularCities;
        $areas = Area::where('city_id', $selectedCity->id)->orderBy('name')->get();

        $pageTitle = "Buy, Sell & Find Services in {$city->name}";

        $activeFilterPills = $this->buildActiveFilterPills($filters, $selectedCategory, $selectedSubcategory, $selectedCity, $selectedState);

        return view('listings.index', compact(
            'listings',
            'categories',
            'popularCities',
            'states',
            'cities',
            'areas',
            'filters',
            'selectedCategory',
            'selectedSubcategory',
            'selectedCity',
            'selectedState',
            'activeFilterPills',
            'pageTitle'
        ));
    }

    /**
     * City + Category dedicated combination page.
     */
    public function byCityAndCategory(Request $request, string $city_slug, string $category_slug): View
    {
        $city = City::with('state')->where('slug', $city_slug)->firstOrFail();
        $category = Category::where('slug', $category_slug)->firstOrFail();

        $filters = array_merge($request->all(), [
            'city' => $city->slug,
            'category' => $category->slug,
        ]);

        $query = Listing::active()
            ->filter($filters)
            ->with(['category', 'subcategory', 'city', 'area', 'state', 'images', 'user']);

        $listings = $query->paginate(12)->withQueryString();

        $categories = Category::with('subcategories')->orderBy('order')->get();
        $popularCities = City::where('is_popular', true)->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $selectedCategory = $category;
        $selectedSubcategory = ! empty($filters['subcategory']) ? Subcategory::where('slug', $filters['subcategory'])->where('category_id', $category->id)->first() : null;
        $selectedCity = $city;
        $selectedState = $city->state;

        $cities = $selectedState ? City::where('state_id', $selectedState->id)->orderBy('name')->get() : $popularCities;
        $areas = Area::where('city_id', $selectedCity->id)->orderBy('name')->get();

        $pageTitle = "{$category->name} in {$city->name}";

        $activeFilterPills = $this->buildActiveFilterPills($filters, $selectedCategory, $selectedSubcategory, $selectedCity, $selectedState);

        return view('listings.index', compact(
            'listings',
            'categories',
            'popularCities',
            'states',
            'cities',
            'areas',
            'filters',
            'selectedCategory',
            'selectedSubcategory',
            'selectedCity',
            'selectedState',
            'activeFilterPills',
            'pageTitle'
        ));
    }

    /**
     * Build active filter pills for view rendering.
     *
     * @param  array<string, mixed>  $filters
     * @return array<int, array<string, mixed>>
     */
    private function buildActiveFilterPills(
        array $filters,
        ?Category $selectedCategory = null,
        ?Subcategory $selectedSubcategory = null,
        ?City $selectedCity = null,
        ?State $selectedState = null
    ): array {
        $pills = [];

        if (! empty($filters['search'])) {
            $pills[] = [
                'type' => 'search',
                'label' => 'Keyword: "'.Str::limit((string) $filters['search'], 25).'"',
                'param' => 'search',
            ];
        }

        if (! empty($filters['type'])) {
            $pills[] = [
                'type' => 'type',
                'label' => 'Type: '.ucfirst((string) $filters['type']),
                'param' => 'type',
            ];
        }

        if ($selectedCategory) {
            $pills[] = [
                'type' => 'category',
                'label' => 'Category: '.$selectedCategory->name,
                'param' => 'category',
            ];
        }

        if ($selectedSubcategory) {
            $pills[] = [
                'type' => 'subcategory',
                'label' => 'Subcategory: '.$selectedSubcategory->name,
                'param' => 'subcategory',
            ];
        }

        if ($selectedState && ! $selectedCity) {
            $pills[] = [
                'type' => 'state',
                'label' => 'State: '.$selectedState->name,
                'param' => 'state_id',
            ];
        }

        if ($selectedCity) {
            $pills[] = [
                'type' => 'city',
                'label' => 'City: '.$selectedCity->name,
                'param' => 'city',
            ];
        }

        if (! empty($filters['min_price']) || ! empty($filters['max_price'])) {
            $minFormatted = ! empty($filters['min_price']) ? '₹'.number_format((float) $filters['min_price']) : '₹0';
            $maxFormatted = ! empty($filters['max_price']) ? '₹'.number_format((float) $filters['max_price']) : 'Any';
            $pills[] = [
                'type' => 'price',
                'label' => "Price: {$minFormatted} - {$maxFormatted}",
                'params' => ['min_price', 'max_price'],
            ];
        }

        if (! empty($filters['condition'])) {
            $conditions = is_array($filters['condition']) ? $filters['condition'] : [$filters['condition']];
            foreach ($conditions as $cond) {
                if (empty($cond)) {
                    continue;
                }
                $pills[] = [
                    'type' => 'condition',
                    'label' => 'Condition: '.$cond,
                    'param' => 'condition',
                    'value' => $cond,
                ];
            }
        }

        if (! empty($filters['is_negotiable'])) {
            $pills[] = [
                'type' => 'is_negotiable',
                'label' => 'Negotiable Only',
                'param' => 'is_negotiable',
            ];
        }

        if (! empty($filters['is_featured'])) {
            $pills[] = [
                'type' => 'is_featured',
                'label' => 'Featured Only',
                'param' => 'is_featured',
            ];
        }

        if (! empty($filters['with_photos'])) {
            $pills[] = [
                'type' => 'with_photos',
                'label' => 'With Photos Only',
                'param' => 'with_photos',
            ];
        }

        if (! empty($filters['posted_within'])) {
            $dateLabel = match ($filters['posted_within']) {
                'today', '24h' => 'Last 24 Hours',
                'week', '7d' => 'Last 7 Days',
                'month', '30d' => 'Last 30 Days',
                default => (string) $filters['posted_within'],
            };
            $pills[] = [
                'type' => 'posted_within',
                'label' => 'Posted: '.$dateLabel,
                'param' => 'posted_within',
            ];
        }

        return $pills;
    }

    /**
     * Display the specified listing detail page.
     */
    public function show(string $slug): View
    {
        $listing = Listing::with(['category', 'subcategory', 'country', 'state', 'city', 'area', 'images', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views count
        $listing->increment('views_count');

        // Related listings in same category or city
        $relatedListings = Listing::active()
            ->where('id', '!=', $listing->id)
            ->where(function ($q) use ($listing) {
                $q->where('category_id', $listing->category_id)
                    ->orWhere('city_id', $listing->city_id);
            })
            ->with(['category', 'city', 'area', 'images', 'user'])
            ->take(4)
            ->get();

        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = Favorite::where('user_id', Auth::id())
                ->where('listing_id', $listing->id)
                ->exists();
        }

        return view('listings.show', compact('listing', 'relatedListings', 'isFavorited'));
    }

    /**
     * Show the form for creating a new listing.
     */
    public function create(): View
    {
        $categories = Category::with('subcategories')->orderBy('order')->get();
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $popularCities = City::where('is_popular', true)->orderBy('name')->get();

        return view('listings.create', compact('categories', 'countries', 'states', 'popularCities'));
    }

    /**
     * Store a newly created listing in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'is_negotiable' => ['nullable', 'boolean'],
            'condition' => ['nullable', 'string', 'in:Brand New,Like New,Good,Fair'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $user = Auth::user();

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug.'-'.Str::lower(Str::random(6));

        $listing = Listing::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_negotiable' => $request->boolean('is_negotiable'),
            'condition' => $validated['type'] === 'product' ? ($validated['condition'] ?? 'Good') : null,
            'country_id' => $validated['country_id'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'phone' => $validated['phone'] ?? $user->phone,
            'whatsapp' => $validated['whatsapp'] ?? $user->phone,
            'email' => $validated['email'] ?? $user->email,
            'status' => 'active',
            'is_featured' => false,
            'views_count' => 0,
        ]);

        // Process Uploaded Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => ($index === 0),
                    'order' => $index,
                ]);
            }
        } else {
            // Provide a default image based on category
            $defaultImage = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80';
            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $defaultImage,
                'is_primary' => true,
                'order' => 0,
            ]);
        }

        return redirect()->route('listings.show', $listing->slug)->with('success', 'Your listing has been posted successfully and is now live on the marketplace!');
    }

    /**
     * Show form for editing listing.
     */
    public function edit(int $id): View
    {
        $listing = Listing::with(['images', 'category.subcategories'])->where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::with('subcategories')->orderBy('order')->get();
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = $listing->state_id ? City::where('state_id', $listing->state_id)->get() : collect();
        $areas = $listing->city_id ? Area::where('city_id', $listing->city_id)->get() : collect();

        return view('listings.edit', compact('listing', 'categories', 'countries', 'states', 'cities', 'areas'));
    }

    /**
     * Update an existing listing.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'is_negotiable' => ['nullable', 'boolean'],
            'condition' => ['nullable', 'string', 'in:Brand New,Like New,Good,Fair'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'status' => ['required', 'in:active,sold,inactive'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $listing->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_negotiable' => $request->boolean('is_negotiable'),
            'condition' => $validated['type'] === 'product' ? ($validated['condition'] ?? 'Good') : null,
            'country_id' => $validated['country_id'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'status' => $validated['status'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'],
            'email' => $validated['email'],
        ]);

        if ($request->hasFile('images')) {
            $existingCount = $listing->images()->count();
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => ($existingCount === 0 && $index === 0),
                    'order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('listings.show', $listing->slug)->with('success', 'Listing updated successfully!');
    }

    /**
     * Delete a listing.
     */
    public function destroy(int $id): RedirectResponse
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        // Delete images from disk if local
        foreach ($listing->images as $image) {
            if (! str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $listing->delete();

        return redirect()->route('dashboard')->with('success', 'Listing deleted successfully.');
    }

    /**
     * Toggle listing status between active and sold.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        $newStatus = ($listing->status === 'active') ? 'sold' : 'active';
        $listing->update(['status' => $newStatus]);

        $statusText = $newStatus === 'sold' ? 'marked as Sold' : 're-activated';

        return back()->with('success', "Listing {$statusText} successfully.");
    }

    /**
     * Submit an inquiry to the seller.
     */
    public function sendInquiry(Request $request, int $id): RedirectResponse
    {
        $listing = Listing::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        Inquiry::create([
            'listing_id' => $listing->id,
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Your inquiry has been sent to the seller!');
    }

    /**
     * Toggle favorite for a listing.
     */
    public function toggleFavorite(int $id): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('info', 'Please sign in to save favorite listings.');
        }

        $userId = Auth::id();
        $favorite = Favorite::where('user_id', $userId)->where('listing_id', $id)->first();

        if ($favorite) {
            $favorite->delete();
            $msg = 'Removed from your saved items.';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'listing_id' => $id,
            ]);
            $msg = 'Added to your saved items!';
        }

        return back()->with('success', $msg);
    }
}
