<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Listing;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the marketplace home page.
     */
    public function index(): View
    {
        // 1. Categories with listing counts
        $categories = Category::withCount(['listings' => fn ($q) => $q->where('status', 'active')])
            ->with(['subcategories' => fn ($q) => $q->take(5)])
            ->orderBy('order')
            ->get();

        // 2. Popular Cities with active listing counts
        $popularCities = City::where('is_popular', true)
            ->withCount(['listings' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();

        // 3. Featured Listings
        $featuredListings = Listing::active()
            ->featured()
            ->with(['category', 'city', 'area', 'images', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // 4. Recent Products
        $recentProducts = Listing::active()
            ->where('type', 'product')
            ->with(['category', 'city', 'area', 'images', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // 5. Recent Services
        $recentServices = Listing::active()
            ->where('type', 'service')
            ->with(['category', 'city', 'area', 'images', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // 6. Metrics / Stats
        $stats = [
            'total_listings' => Listing::active()->count(),
            'total_products' => Listing::active()->where('type', 'product')->count(),
            'total_services' => Listing::active()->where('type', 'service')->count(),
            'total_cities' => City::has('listings')->count(),
            'total_users' => User::count(),
        ];

        return view('home', compact(
            'categories',
            'popularCities',
            'featuredListings',
            'recentProducts',
            'recentServices',
            'stats'
        ));
    }
}
