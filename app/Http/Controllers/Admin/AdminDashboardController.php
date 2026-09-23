<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Panel Dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'sold_listings' => Listing::where('status', 'sold')->count(),
            'inactive_listings' => Listing::where('status', 'inactive')->count(),
            'featured_listings' => Listing::where('is_featured', true)->count(),
            'total_categories' => Category::count(),
            'total_cities' => City::count(),
            'total_inquiries' => Inquiry::count(),
            'total_views' => Listing::sum('views_count'),
        ];

        // Recent Listings
        $recentListings = Listing::with(['user', 'category', 'city'])
            ->latest()
            ->take(8)
            ->get();

        // Recent Registered Users
        $recentUsers = User::withCount('listings')
            ->latest()
            ->take(6)
            ->get();

        // Recent Inquiries
        $recentInquiries = Inquiry::with(['listing', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentListings', 'recentUsers', 'recentInquiries'));
    }
}
