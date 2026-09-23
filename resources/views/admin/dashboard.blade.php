@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'System Overview & Marketplace Metrics')

@section('content')
<div class="space-y-8">

    <!-- 4 High Impact Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Listings -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block mb-1">Total Listings</span>
                <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_listings']) }}</span>
                <span class="text-[11px] font-semibold text-emerald-600 block mt-1">
                    {{ $stats['active_listings'] }} active • {{ $stats['sold_listings'] }} sold
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block mb-1">Registered Users</span>
                <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_users']) }}</span>
                <span class="text-[11px] font-semibold text-teal-600 block mt-1">
                    {{ $stats['total_admins'] }} administrators
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Total Inquiries -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block mb-1">Buyer Inquiries</span>
                <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_inquiries']) }}</span>
                <span class="text-[11px] font-semibold text-indigo-600 block mt-1">Direct inquiries sent</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
        </div>

        <!-- Total Ad Views -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block mb-1">Total Ad Impressions</span>
                <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_views']) }}</span>
                <span class="text-[11px] font-semibold text-slate-500 block mt-1">Across {{ $stats['total_cities'] }} cities</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
        </div>

    </div>

    <!-- Quick Shortcuts Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.listings.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">📋</span>
            <div>
                <span class="text-xs font-bold text-slate-900 block">Manage Listings</span>
                <span class="text-[10px] text-slate-400">Edit, feature or remove</span>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold">👥</span>
            <div>
                <span class="text-xs font-bold text-slate-900 block">Manage Users</span>
                <span class="text-[10px] text-slate-400">Promote admins, delete</span>
            </div>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">🗂️</span>
            <div>
                <span class="text-xs font-bold text-slate-900 block">Categories</span>
                <span class="text-[10px] text-slate-400">Create & manage subs</span>
            </div>
        </a>

        <a href="{{ route('admin.locations.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold">📍</span>
            <div>
                <span class="text-xs font-bold text-slate-900 block">Locations</span>
                <span class="text-[10px] text-slate-400">Cities & areas</span>
            </div>
        </a>
    </div>

    <!-- Recent Listings Management Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs space-y-4 p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Recent Ads on Marketplace</h3>
                <p class="text-xs text-slate-500">Live listings posted across all categories and cities</p>
            </div>
            <a href="{{ route('admin.listings.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800">
                View All Listings →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider font-bold text-slate-500">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Ad Item</th>
                        <th class="py-3 px-3">Price</th>
                        <th class="py-3 px-3">Category</th>
                        <th class="py-3 px-3">Seller</th>
                        <th class="py-3 px-3">Location</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-4 rounded-r-xl text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentListings as $listing)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-semibold">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $listing->primary_image_url }}" class="w-10 h-10 rounded-lg object-cover">
                                    <div>
                                        <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" class="text-slate-900 hover:text-teal-700 font-bold block max-w-xs truncate">
                                            {{ $listing->title }}
                                        </a>
                                        <span class="text-[10px] text-slate-400">#AD-{{ $listing->id }} • {{ $listing->type }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-3 font-black text-slate-900">
                                {{ $listing->formatted_price }}
                            </td>
                            <td class="py-3.5 px-3 font-medium text-slate-600">
                                {{ $listing->category?->name }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-600">
                                {{ $listing->user->name }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-500">
                                {{ $listing->city?->name ?? 'India' }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $listing->status === 'active' ? 'bg-teal-50 text-teal-700' : ($listing->status === 'sold' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $listing->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5">
                                <form action="{{ route('admin.listings.featured', $listing->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 rounded-lg border {{ $listing->is_featured ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-slate-50 text-slate-400 border-slate-200 hover:text-amber-600' }}" title="Toggle Featured">
                                        ★
                                    </button>
                                </form>

                                <a href="{{ route('admin.listings.edit', $listing->id) }}" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 inline-block" title="Edit Listing">
                                    ✏️
                                </a>

                                <form action="{{ route('admin.listings.destroy', $listing->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this listing permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50" title="Delete Listing">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2 Column Section: Recent Users & Recent Inquiries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Users -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900 tracking-tight">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-teal-700">All Users →</a>
            </div>

            <div class="space-y-3">
                @foreach($recentUsers as $u)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <img src="{{ $u->avatar_url }}" class="w-10 h-10 rounded-xl object-cover">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-900">{{ $u->name }}</span>
                                    @if($u->is_admin)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-teal-600 text-white">ADMIN</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400">{{ $u->email }} • {{ $u->city ?? 'India' }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-700">{{ $u->listings_count }} ads</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900 tracking-tight">Recent Inquiries</h3>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-teal-700">All Inquiries →</a>
            </div>

            <div class="space-y-3">
                @foreach($recentInquiries as $inq)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-900 font-bold">{{ $inq->name }}</span>
                            <span class="text-slate-400 text-[10px]">{{ $inq->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-600 italic">"{{ Str::limit($inq->message, 80) }}"</p>
                        <p class="text-[10px] text-teal-700 font-medium">For: {{ $inq->listing?->title }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
