@extends('layouts.app')

@section('title', 'My Dashboard & Ads | Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Profile & Metrics Header -->
    <div class="bg-gradient-to-r from-[#002f34] via-[#00474e] to-[#001f22] text-white rounded-3xl p-6 sm:p-8 shadow-lg">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-teal-800/60">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-teal-400">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">{{ $user->name }}</h1>
                    <p class="text-xs text-slate-300">{{ $user->email }} • {{ $user->phone ?? 'No phone added' }}</p>
                    <p class="text-[11px] text-teal-300 mt-0.5">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors">
                    Edit Profile
                </a>
                <a href="{{ route('listings.create') }}" class="px-5 py-2.5 rounded-xl bg-teal-400 hover:bg-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition-colors shadow-md flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    <span>Post New Ad</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 text-center sm:text-left">
            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                <span class="text-2xl sm:text-3xl font-black text-white block">{{ $totalListings }}</span>
                <span class="text-xs text-slate-300 font-medium">Total Ads Posted</span>
            </div>
            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                <span class="text-2xl sm:text-3xl font-black text-teal-400 block">{{ $activeListings }}</span>
                <span class="text-xs text-slate-300 font-medium">Active Live Ads</span>
            </div>
            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                <span class="text-2xl sm:text-3xl font-black text-amber-400 block">{{ $soldListings }}</span>
                <span class="text-xs text-slate-300 font-medium">Items Sold</span>
            </div>
            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                <span class="text-2xl sm:text-3xl font-black text-sky-400 block">{{ number_format($totalViews) }}</span>
                <span class="text-xs text-slate-300 font-medium">Total Ad Views</span>
            </div>
        </div>
    </div>

    <!-- Main Section: My Ads Tabs & Management -->
    <div class="space-y-6">
        
        <!-- Status Tabs Bar -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-wrap gap-4">
            <div class="flex items-center space-x-2 text-xs font-bold">
                <a href="{{ route('dashboard', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $status === 'all' ? 'bg-[#002f34] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    All Ads ({{ $totalListings }})
                </a>
                <a href="{{ route('dashboard', ['status' => 'active']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $status === 'active' ? 'bg-[#002f34] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Active ({{ $activeListings }})
                </a>
                <a href="{{ route('dashboard', ['status' => 'sold']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $status === 'sold' ? 'bg-[#002f34] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Sold ({{ $soldListings }})
                </a>
            </div>

            <span class="text-xs text-slate-400">Showing page {{ $listings->currentPage() }} of {{ $listings->lastPage() }}</span>
        </div>

        <!-- Listings List -->
        @if($listings->count() > 0)
            <div class="space-y-4">
                @foreach($listings as $item)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:border-slate-300 transition-all">
                        
                        <!-- Thumbnail and Details -->
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <a href="{{ route('listings.show', $item->slug) }}" class="relative w-24 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                <img src="{{ $item->primary_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            </a>

                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $item->status === 'active' ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">
                                        {{ $item->category?->name }} • {{ $item->type }}
                                    </span>
                                </div>

                                <a href="{{ route('listings.show', $item->slug) }}" class="block font-bold text-sm sm:text-base text-slate-900 hover:text-teal-700 line-clamp-1">
                                    {{ $item->title }}
                                </a>

                                <div class="flex items-center gap-4 text-xs text-slate-500 font-medium">
                                    <span class="font-black text-slate-900">{{ $item->formatted_price }}</span>
                                    <span>•</span>
                                    <span>{{ $item->location_string }}</span>
                                    <span>•</span>
                                    <span>{{ $item->views_count }} views</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Bar -->
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 shrink-0">
                            <!-- Toggle Sold/Active -->
                            <form action="{{ route('listings.toggle-status', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold border transition-colors {{ $item->status === 'active' ? 'border-amber-300 text-amber-800 bg-amber-50 hover:bg-amber-100' : 'border-teal-300 text-teal-800 bg-teal-50 hover:bg-teal-100' }}">
                                    {{ $item->status === 'active' ? 'Mark as Sold' : 'Reactivate' }}
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('listings.edit', $item->id) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-700 hover:bg-slate-100 transition-colors">
                                Edit
                            </a>

                            <!-- View Public Ad -->
                            <a href="{{ route('listings.show', $item->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 transition-colors">
                                View
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('listings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors" title="Delete listing">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $listings->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto">
                <div class="w-14 h-14 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No ads in this tab</h3>
                <p class="text-xs text-slate-500 mb-4">You have no listings under the selected filter.</p>
                <a href="{{ route('listings.create') }}" class="px-5 py-2.5 rounded-xl bg-teal-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-teal-700 transition-colors inline-block">
                    + Post an Ad Now
                </a>
            </div>
        @endif

    </div>

    <!-- Inquiries Inbox & Saved Favorites Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-6 border-t border-slate-200">
        
        <!-- Recent Inquiries Inbox -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center justify-between">
                <span>Recent Buyer Inquiries</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $inquiries->count() }} messages</span>
            </h2>

            @if($inquiries->count() > 0)
                <div class="space-y-3">
                    @foreach($inquiries as $inquiry)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-900">{{ $inquiry->name }} ({{ $inquiry->email }})</span>
                                <span class="text-slate-400">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 italic">"{{ $inquiry->message }}"</p>
                            <p class="text-[11px] text-teal-700 font-medium">Regarding: <span class="font-bold">{{ $inquiry->listing->title }}</span></p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-4 text-center">No buyer inquiries received yet. They will appear here when buyers contact you.</p>
            @endif
        </div>

        <!-- Saved Favorites -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center justify-between">
                <span>Your Saved / Favorite Ads</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-600">{{ $favorites->count() }} saved</span>
            </h2>

            @if($favorites->count() > 0)
                <div class="space-y-3">
                    @foreach($favorites as $fav)
                        @if($fav->listing)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $fav->listing->primary_image_url }}" class="w-12 h-12 rounded-lg object-cover">
                                    <div>
                                        <a href="{{ route('listings.show', $fav->listing->slug) }}" class="text-xs font-bold text-slate-900 hover:text-teal-700 line-clamp-1">
                                            {{ $fav->listing->title }}
                                        </a>
                                        <span class="text-xs font-black text-slate-800">{{ $fav->listing->formatted_price }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('listings.show', $fav->listing->slug) }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">
                                    View Ad →
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-4 text-center">No saved ads yet. Click the heart icon on any ad to bookmark it.</p>
            @endif
        </div>

    </div>

</div>
@endsection
