<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingApiController extends Controller
{
    /**
     * Get listings with filters and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search',
            'type',
            'category_id',
            'subcategory_id',
            'city_id',
            'state_id',
            'min_price',
            'max_price',
            'condition',
            'sort',
        ]);

        $listings = Listing::active()
            ->filter($filters)
            ->with(['category', 'subcategory', 'city', 'area', 'state', 'images', 'user'])
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $listings,
        ]);
    }

    /**
     * Get a single listing.
     */
    public function show(int $id): JsonResponse
    {
        $listing = Listing::with(['category', 'subcategory', 'country', 'state', 'city', 'area', 'images', 'user'])
            ->findOrFail($id);

        $listing->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => $listing,
        ]);
    }

    /**
     * Store a new listing (Sanctum protected).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_negotiable' => ['nullable', 'boolean'],
            'condition' => ['nullable', 'string', 'in:Brand New,Like New,Good,Fair'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'images' => ['nullable', 'array'],
        ]);

        $user = $request->user();
        $slug = Str::slug($validated['title']).'-'.Str::lower(Str::random(6));

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
            'email' => $user->email,
            'status' => 'active',
            'is_featured' => false,
            'views_count' => 0,
        ]);

        // If image URLs or files are passed
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => ($index === 0),
                    'order' => $index,
                ]);
            }
        } elseif ($request->filled('image_urls')) {
            foreach ($request->input('image_urls') as $index => $url) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $url,
                    'is_primary' => ($index === 0),
                    'order' => $index,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Listing created successfully.',
            'data' => $listing->load(['category', 'images', 'city', 'area']),
        ], 201);
    }

    /**
     * Categories list with subcategories.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::with('subcategories')->orderBy('order')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * Locations list.
     */
    public function locations(): JsonResponse
    {
        $countries = Country::with(['states.cities.areas'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $countries,
        ]);
    }
}
