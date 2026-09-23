@extends('layouts.app')

@section('title', 'Marketplace - Buy & Sell Cars, Mobiles, Real Estate, Furniture & Hire Local Services')

@section('content')
<div class="space-y-12 pb-16">

    <!-- HERO SEARCH SECTION -->
    <section class="relative bg-gradient-to-br from-[#002f34] via-[#00474e] to-[#001f22] text-white py-14 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-md">
        <!-- Abstract Background Ornaments -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/20 text-teal-300 border border-teal-500/30 mb-4">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span> Over {{ number_format($stats['total_listings']) }} Verified Listings Live Now
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-4 leading-tight">
                Buy, Sell & Hire <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-amber-300">Anything Locally</span>
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-8">
                Discover incredible deals on phones, cars, electronics, properties, or hire top-rated service professionals in your city.
            </p>

            <!-- Hero Main Search Box -->
            <form action="{{ route('listings.index') }}" method="GET" class="bg-white p-2 sm:p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row items-center gap-2 max-w-4xl mx-auto text-slate-800">
                <!-- Location Selector -->
                <div class="w-full md:w-56 flex items-center px-3 py-2 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5 text-teal-600 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <select name="city" class="w-full bg-transparent text-sm font-semibold text-slate-800 focus:outline-none cursor-pointer">
                        <option value="">All Locations (India)</option>
                        @foreach($popularCities as $c)
                            <option value="{{ $c->slug }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Selector -->
                <div class="w-full md:w-52 flex items-center px-3 py-2 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5 text-teal-600 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    <select name="category" class="w-full bg-transparent text-sm font-semibold text-slate-800 focus:outline-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Input -->
                <div class="w-full flex-1 flex items-center px-3 py-2 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="What are you looking for today? (e.g. iPhone, Creta, AC Repair...)" class="w-full bg-transparent text-sm text-slate-800 placeholder-slate-400 focus:outline-none font-medium">
                </div>

                <!-- Search Submit CTA -->
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-slate-950 font-black text-sm uppercase tracking-wider rounded-xl transition-all shadow-lg hover:shadow-xl shrink-0 flex items-center justify-center gap-2">
                    <span>Search</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </button>
            </form>

            <!-- Popular Quick Searches -->
            <div class="mt-6 flex items-center justify-center flex-wrap gap-2 text-xs text-slate-300">
                <span class="text-slate-400 font-semibold">Popular Searches:</span>
                <a href="{{ route('listings.index', ['search' => 'iPhone']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">iPhone 15</a>
                <a href="{{ route('listings.index', ['search' => 'Creta']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">Hyundai Creta</a>
                <a href="{{ route('listings.index', ['search' => 'Apartment']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">2 BHK Flats</a>
                <a href="{{ route('listings.index', ['search' => 'AC Repair']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">AC Repair</a>
                <a href="{{ route('listings.index', ['search' => 'PlayStation']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">PS5</a>
            </div>
        </div>
    </section>

    <!-- POPULAR CITIES BAR -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-xs font-black uppercase text-slate-800 tracking-wider">Explore Cities</h3>
                    <p class="text-[11px] text-slate-500">Discover active ads in your area</p>
                </div>
            </div>

            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 no-scrollbar">
                @foreach($popularCities as $city)
                    <a href="{{ route('city.show', $city->slug) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold border border-slate-200 hover:border-teal-500 hover:bg-teal-50 hover:text-teal-800 transition-all shrink-0 flex items-center gap-1.5 bg-slate-50/60">
                        <span>{{ $city->name }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-200 text-slate-700 font-bold">{{ $city->listings_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- EXPLORE BY CATEGORIES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Browse All Categories</h2>
                <p class="text-xs text-slate-500">Find products, services, vehicles, electronics and more</p>
            </div>
            <a href="{{ route('listings.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
                <span>View All</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-4">
            @php
                $categoryIcons = [
                    'mobiles' => ['bg' => 'bg-blue-50 text-blue-600', 'svg' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    'vehicles' => ['bg' => 'bg-emerald-50 text-emerald-600', 'svg' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    'electronics' => ['bg' => 'bg-indigo-50 text-indigo-600', 'svg' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    'real-estate' => ['bg' => 'bg-rose-50 text-rose-600', 'svg' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    'home-furniture' => ['bg' => 'bg-amber-50 text-amber-600', 'svg' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    'services' => ['bg' => 'bg-teal-50 text-teal-600', 'svg' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    'fashion' => ['bg' => 'bg-purple-50 text-purple-600', 'svg' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    'hobbies-sports' => ['bg' => 'bg-sky-50 text-sky-600', 'svg' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach($categories as $cat)
                @php
                    $style = $categoryIcons[$cat->slug] ?? ['bg' => 'bg-slate-50 text-slate-600', 'svg' => 'M4 6h16M4 12h16M4 18h16'];
                @endphp
                <a href="{{ route('category.show', $cat->slug) }}" class="bg-white p-4 rounded-xl border border-slate-200/80 hover:border-teal-500 hover:shadow-md transition-all text-center flex flex-col items-center justify-between group">
                    <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['svg'] }}"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 leading-tight">
                        {{ $cat->name }}
                    </span>
                    <span class="text-[10px] text-slate-400 mt-1">
                        {{ $cat->listings_count }} ads
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- FEATURED ADS SHOWCASE -->
    @if($featuredListings->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Featured Recommendations</h2>
                </div>
                <a href="{{ route('listings.index', ['is_featured' => 1]) }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
                    <span>View all featured</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($featuredListings as $listing)
                    <x-listing-card :listing="$listing" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- RECENT PRODUCTS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-[#002f34] text-white">PRODUCTS</span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Fresh Product Listings</h2>
                </div>
                <p class="text-xs text-slate-500">Mobiles, cars, electronics, fashion and household items</p>
            </div>
            <a href="{{ route('listings.index', ['type' => 'product']) }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
                <span>See all products</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($recentProducts as $listing)
                <x-listing-card :listing="$listing" />
            @endforeach
        </div>
    </section>

    <!-- LOCAL SERVICES SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-[#002f34] text-white rounded-3xl p-6 sm:p-10 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <span class="px-2.5 py-1 rounded-md text-xs font-black uppercase bg-indigo-500 text-white tracking-wider">
                        PROFESSIONAL SERVICES
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight mt-2">
                        Hire Trusted Local Professionals
                    </h2>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-xl">
                        Find verified cleaners, AC technicians, tutors, developers, photographers and legal experts near you.
                    </p>
                </div>
                <a href="{{ route('listings.index', ['type' => 'service']) }}" class="px-5 py-2.5 rounded-xl bg-white text-slate-950 hover:bg-teal-50 font-bold text-xs uppercase tracking-wider transition-colors shrink-0 flex items-center gap-2 self-start md:self-auto">
                    <span>Browse All Services</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($recentServices as $listing)
                    <x-listing-card :listing="$listing" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- POST AD CTA BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-amber-400 via-orange-400 to-rose-400 rounded-3xl p-8 sm:p-12 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-2xl text-slate-950">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-black/10 text-slate-950">
                    100% Free Classifieds
                </span>
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight mt-2 mb-2">
                    Have something to sell or a service to offer?
                </h2>
                <p class="text-slate-900/80 text-sm font-medium">
                    Post your ad in less than 30 seconds and connect directly with thousands of verified local buyers in your city!
                </p>
            </div>
            <a href="{{ route('listings.create') }}" class="px-8 py-4 bg-slate-950 hover:bg-slate-900 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl hover:scale-105 transition-transform shrink-0 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                <span>Post Your Free Ad</span>
            </a>
        </div>
    </section>

</div>
@endsection
