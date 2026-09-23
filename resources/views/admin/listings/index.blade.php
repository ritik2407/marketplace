@extends('admin.layouts.admin')

@section('title', 'Manage Listings')
@section('header_title', 'All Marketplace Listings')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-xs">
        <form action="{{ route('admin.listings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
            
            <!-- Search -->
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, description, seller..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500 font-medium">
            </div>

            <!-- Category -->
            <div>
                <select name="category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500 font-medium">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City -->
            <div>
                <select name="city_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500 font-medium">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500 font-medium">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-[#002f34] text-white font-bold rounded-xl hover:bg-[#004d55] transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.listings.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">
                Found <span class="text-slate-900 font-black">{{ $listings->total() }}</span> total listings
            </span>
            <span class="text-xs text-slate-400">Page {{ $listings->currentPage() }} of {{ $listings->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Item Details</th>
                        <th class="py-3 px-3">Price</th>
                        <th class="py-3 px-3">Category</th>
                        <th class="py-3 px-3">Seller Details</th>
                        <th class="py-3 px-3">City / Area</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Views</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($listings as $listing)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-semibold">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $listing->primary_image_url }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                    <div class="max-w-xs">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            @if($listing->is_featured)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-amber-400 text-slate-900">★ FEATURED</span>
                                            @endif
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-slate-200 text-slate-700">{{ $listing->type }}</span>
                                        </div>
                                        <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" class="text-slate-900 hover:text-teal-700 font-bold block truncate">
                                            {{ $listing->title }}
                                        </a>
                                        <span class="text-[10px] text-slate-400">ID: #{{ $listing->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-3 font-black text-slate-900 whitespace-nowrap">
                                {{ $listing->formatted_price }}
                                @if($listing->is_negotiable)
                                    <span class="block text-[9px] text-emerald-600 font-normal">Negotiable</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 font-medium text-slate-600 whitespace-nowrap">
                                {{ $listing->category?->name }}
                            </td>

                            <td class="py-3.5 px-3 text-slate-600 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block">{{ $listing->user->name }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $listing->user->email }}</span>
                            </td>

                            <td class="py-3.5 px-3 text-slate-500 whitespace-nowrap">
                                {{ $listing->location_string }}
                            </td>

                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <form action="{{ route('admin.listings.status', $listing->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-lg px-2 py-1 border {{ $listing->status === 'active' ? 'bg-teal-50 text-teal-800 border-teal-200' : ($listing->status === 'sold' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200') }}">
                                        <option value="active" {{ $listing->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="sold" {{ $listing->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                        <option value="inactive" {{ $listing->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </form>
                            </td>

                            <td class="py-3.5 px-3 font-mono font-bold text-slate-500">
                                {{ $listing->views_count }}
                            </td>

                            <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                <form action="{{ route('admin.listings.featured', $listing->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 rounded-lg border {{ $listing->is_featured ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-slate-50 text-slate-400 border-slate-200 hover:text-amber-600' }}" title="Toggle Featured">
                                        ★
                                    </button>
                                </form>

                                <a href="{{ route('admin.listings.edit', $listing->id) }}" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 inline-block" title="Edit Listing">
                                    ✏️
                                </a>

                                <form action="{{ route('admin.listings.destroy', $listing->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this listing permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50" title="Delete Listing">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-100">
            {{ $listings->links() }}
        </div>
    </div>

</div>
@endsection
