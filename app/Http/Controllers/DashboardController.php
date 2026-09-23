<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');

        $listingsQuery = Listing::where('user_id', $user->id)
            ->with(['category', 'city', 'area', 'images'])
            ->latest();

        if ($status === 'active') {
            $listingsQuery->where('status', 'active');
        } elseif ($status === 'sold') {
            $listingsQuery->where('status', 'sold');
        } elseif ($status === 'inactive') {
            $listingsQuery->where('status', 'inactive');
        }

        $listings = $listingsQuery->paginate(10)->withQueryString();

        // Metrics
        $totalListings = Listing::where('user_id', $user->id)->count();
        $activeListings = Listing::where('user_id', $user->id)->where('status', 'active')->count();
        $soldListings = Listing::where('user_id', $user->id)->where('status', 'sold')->count();
        $totalViews = Listing::where('user_id', $user->id)->sum('views_count');

        // Recent Inquiries for user's listings
        $userListingIds = Listing::where('user_id', $user->id)->pluck('id');
        $inquiries = Inquiry::whereIn('listing_id', $userListingIds)
            ->with('listing')
            ->latest()
            ->take(10)
            ->get();

        // Saved / Favorite items
        $favorites = Favorite::where('user_id', $user->id)
            ->with(['listing.category', 'listing.city', 'listing.images', 'listing.user'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'listings',
            'totalListings',
            'activeListings',
            'soldListings',
            'totalViews',
            'inquiries',
            'favorites',
            'status'
        ));
    }
}
