@extends('layouts.app')

@section('title', $pageTitle . ' | Marketplace')

@php
    $currentCategorySlug = $filters['category'] 
        ?? request('category') 
        ?? ($selectedCategory->slug ?? null)
        ?? (request()->route('category_slug') ?? null);

    $currentSubcategorySlug = $filters['subcategory'] 
        ?? request('subcategory') 
        ?? ($selectedSubcategory->slug ?? null)
        ?? (request()->route('subcategory_slug') ?? null);

    $currentCitySlug = $filters['city'] 
        ?? request('city') 
        ?? ($selectedCity->slug ?? null)
        ?? (request()->route('city_slug') ?? null);

    $currentStateId = $filters['state_id'] 
        ?? request('state_id') 
        ?? ($selectedState->id ?? null);

    $currentAreaId = $filters['area_id'] ?? request('area_id');
    $currentType = $filters['type'] ?? request('type');
    $selectedConditions = (array) ($filters['condition'] ?? request('condition', []));
    $hasActiveCategory = !empty($currentCategorySlug);
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center text-xs text-slate-500 mb-5 flex-wrap gap-1.5" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-teal-700 font-medium transition-colors">Home</a>
        <span>/</span>

        @if(!empty($selectedCity))
            <a href="{{ route('city.show', $selectedCity->slug) }}" class="hover:text-teal-700 font-medium transition-colors">{{ $selectedCity->name }}</a>
            <span>/</span>
        @endif

        @if(!empty($selectedCategory))
            <a href="{{ route('category.show', $selectedCategory->slug) }}" class="hover:text-teal-700 font-medium transition-colors">{{ $selectedCategory->name }}</a>
            @if(!empty($selectedSubcategory))
                <span>/</span>
                <span class="text-slate-900 font-bold">{{ $selectedSubcategory->name }}</span>
            @endif
        @else
            <span class="text-slate-900 font-bold">All Listings</span>
        @endif
    </nav>

    <!-- Page Title & Controls Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ $pageTitle }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Showing <span class="font-bold text-slate-800">{{ number_format($listings->total()) }}</span> verified listings
                @if($currentCitySlug) in <span class="font-semibold text-teal-700">{{ ucfirst($currentCitySlug) }}</span>@endif
                @if(request('search')) matching "<span class="font-semibold text-teal-700">{{ request('search') }}</span>"@endif
            </p>
        </div>

        <!-- Controls: Sort & Mobile Filter Toggle -->
        <div class="flex items-center gap-2.5 self-start md:self-auto flex-wrap">
            <!-- Mobile Filters Trigger Button -->
            <button type="button" onclick="toggleMobileFilterDrawer(true)" class="lg:hidden inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-xs">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filters</span>
                @if(count($activeFilterPills) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] bg-teal-600 text-white font-extrabold">{{ count($activeFilterPills) }}</span>
                @endif
            </button>

            <!-- Quick Sort Dropdown -->
            <div class="flex items-center gap-1.5 bg-white border border-slate-300 rounded-xl px-3 py-1.5 shadow-xs">
                <label for="sort-select" class="text-xs font-semibold text-slate-500 shrink-0">Sort:</label>
                <select id="sort-select" onchange="applySort(this.value)" class="text-xs font-bold bg-transparent text-slate-800 focus:outline-none cursor-pointer pr-1">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newly Listed</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Viewed</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Active Filter Badges Bar (Removable Chips) -->
    @if(count($activeFilterPills) > 0)
        <div class="mb-6 p-3.5 bg-teal-50/70 border border-teal-200/80 rounded-2xl flex items-center justify-between gap-3 flex-wrap animate-in fade-in duration-200">
            <div class="flex items-center gap-2 flex-wrap text-xs">
                <span class="text-xs font-black uppercase tracking-wider text-teal-900 flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Active Filters ({{ count($activeFilterPills) }}):
                </span>

                @foreach($activeFilterPills as $pill)
                    @if(isset($pill['params']))
                        {{-- Multi-param pill e.g. price range --}}
                        <button type="button" onclick="removeMultiParams({{ json_encode($pill['params']) }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-teal-300 text-teal-900 text-xs font-bold hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 transition-colors shadow-2xs group" title="Remove filter">
                            <span>{{ $pill['label'] }}</span>
                            <span class="text-teal-500 group-hover:text-rose-600 font-black text-xs leading-none">✕</span>
                        </button>
                    @elseif(isset($pill['value']))
                        {{-- Array element pill e.g. condition[] --}}
                        <button type="button" onclick="removeArrayParam('{{ $pill['param'] }}', '{{ $pill['value'] }}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-teal-300 text-teal-900 text-xs font-bold hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 transition-colors shadow-2xs group" title="Remove filter">
                            <span>{{ $pill['label'] }}</span>
                            <span class="text-teal-500 group-hover:text-rose-600 font-black text-xs leading-none">✕</span>
                        </button>
                    @else
                        {{-- Single param pill --}}
                        <button type="button" onclick="removeSingleParam('{{ $pill['param'] }}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-teal-300 text-teal-900 text-xs font-bold hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 transition-colors shadow-2xs group" title="Remove filter">
                            <span>{{ $pill['label'] }}</span>
                            <span class="text-teal-500 group-hover:text-rose-600 font-black text-xs leading-none">✕</span>
                        </button>
                    @endif
                @endforeach
            </div>

            <a href="{{ route('listings.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline px-2.5 py-1 rounded-lg transition-colors shrink-0 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Clear All
            </a>
        </div>
    @endif

    <!-- Main 2-Column Layout: Sidebar Filters + Listings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        <!-- Sidebar Filters (Desktop) -->
        <aside class="hidden lg:block lg:col-span-1 sticky top-24">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Products
                    </h3>
                    @if(count($activeFilterPills) > 0)
                        <a href="{{ route('listings.index') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700">
                            Reset All
                        </a>
                    @endif
                </div>

                <!-- Shared Filter Form -->
                <form id="desktop-filter-form" action="{{ route('listings.index') }}" method="GET" class="space-y-6">
                    <!-- Preserve sort & page -->
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
                    
                    <!-- 1. Search Query Input -->
                    <div>
                        <label for="desktop-search-input" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Search Keyword</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="desktop-search-input"
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="e.g. iPhone, Toyota, Sofa..." 
                                class="w-full text-xs pl-8 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white text-slate-800 font-medium transition-all"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            @if(request('search'))
                                <button type="button" onclick="removeSingleParam('search')" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold p-0.5">✕</button>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Listing Type (All, Product, Service) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Listing Type</label>
                        <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold text-center">
                            <button type="button" onclick="applySingleFilter('type', '')" class="py-1.5 rounded-lg transition-colors {{ empty($currentType) ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                                All
                            </button>
                            <button type="button" onclick="applySingleFilter('type', 'product')" class="py-1.5 rounded-lg transition-colors {{ $currentType === 'product' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                                Products
                            </button>
                            <button type="button" onclick="applySingleFilter('type', 'service')" class="py-1.5 rounded-lg transition-colors {{ $currentType === 'service' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                                Services
                            </button>
                        </div>
                    </div>

                    <!-- 3. Category & Subcategory Dynamic Filter -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Categories</label>
                            @if($hasActiveCategory)
                                <button type="button" onclick="removeMultiParams(['category', 'subcategory'])" class="text-[10px] font-bold text-teal-700 hover:underline">Clear</button>
                            @endif
                        </div>
                        <div class="space-y-1 text-xs max-h-64 overflow-y-auto pr-1">
                            <!-- All Categories Option -->
                            <button type="button" onclick="removeMultiParams(['category', 'subcategory'])" class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all text-left {{ !$hasActiveCategory ? 'bg-teal-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium' }}">
                                <span>All Categories</span>
                                @if(!$hasActiveCategory)
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </button>

                            @foreach($categories as $cat)
                                @php
                                    $isCatSelected = ($currentCategorySlug === $cat->slug || ($selectedCategory && $selectedCategory->id === $cat->id));
                                @endphp
                                <div>
                                    <button type="button" onclick="applySingleFilter('category', '{{ $cat->slug }}', ['subcategory'])" class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all text-left group {{ $isCatSelected ? 'bg-teal-50 text-teal-900 font-bold border-l-4 border-teal-600 shadow-2xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }}">
                                        <span class="truncate">{{ $cat->name }}</span>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $isCatSelected ? 'bg-teal-200/70 text-teal-900 font-bold' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200' }}">{{ $cat->subcategories->count() }}</span>
                                            @if($isCatSelected)
                                                <svg class="w-3.5 h-3.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            @endif
                                        </div>
                                    </button>

                                    <!-- Subcategories for selected category -->
                                    @if($isCatSelected && $cat->subcategories->isNotEmpty())
                                        <div class="ml-3 pl-2.5 border-l-2 border-teal-200 my-1 space-y-0.5">
                                            @foreach($cat->subcategories as $sub)
                                                @php
                                                    $isSubSelected = ($currentSubcategorySlug === $sub->slug || ($selectedSubcategory && $selectedSubcategory->id === $sub->id));
                                                @endphp
                                                <button type="button" onclick="applySingleFilter('subcategory', '{{ $sub->slug }}')" class="w-full text-left px-2.5 py-1.5 rounded-lg text-[11px] transition-all flex items-center justify-between {{ $isSubSelected ? 'text-teal-900 font-bold bg-teal-100/70 shadow-2xs' : 'text-slate-600 hover:text-teal-700 hover:bg-teal-50/50' }}">
                                                    <span>• {{ $sub->name }}</span>
                                                    @if($isSubSelected)
                                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. Location Hierarchy Filter (State, City, Area) -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Location</label>
                            @if($currentCitySlug || $currentStateId || $currentAreaId)
                                <button type="button" onclick="removeMultiParams(['city', 'city_id', 'state_id', 'area_id'])" class="text-[10px] font-bold text-teal-700 hover:underline">Clear</button>
                            @endif
                        </div>

                        <!-- State Dropdown -->
                        <div>
                            <select name="state_id" onchange="applySingleFilter('state_id', this.value, ['city', 'city_id', 'area_id'])" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 text-slate-800 font-medium cursor-pointer">
                                <option value="">All States</option>
                                @foreach($states as $st)
                                    <option value="{{ $st->id }}" {{ (string)$currentStateId === (string)$st->id ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City Dropdown -->
                        <div>
                            <select name="city" onchange="applySingleFilter('city', this.value, ['area_id'])" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 text-slate-800 font-medium cursor-pointer">
                                <option value="">All Cities</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->slug }}" {{ $currentCitySlug === $c->slug ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area Dropdown (Visible if City is selected) -->
                        @if($areas->isNotEmpty())
                            <div>
                                <select name="area_id" onchange="applySingleFilter('area_id', this.value)" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 text-slate-800 font-medium cursor-pointer">
                                    <option value="">All Areas in {{ $selectedCity?->name }}</option>
                                    @foreach($areas as $ar)
                                        <option value="{{ $ar->id }}" {{ (string)$currentAreaId === (string)$ar->id ? 'selected' : '' }}>
                                            {{ $ar->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Popular City Quick Chips -->
                        <div class="pt-1 flex flex-wrap gap-1">
                            @foreach($popularCities->take(5) as $pc)
                                <button type="button" onclick="applySingleFilter('city', '{{ $pc->slug }}', ['area_id'])" class="px-2 py-0.5 rounded-full text-[10px] font-semibold border transition-colors {{ $currentCitySlug === $pc->slug ? 'bg-teal-700 text-white border-teal-700' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-teal-500 hover:text-teal-700' }}">
                                    {{ $pc->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- 5. Price Range Filter with Quick Presets -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Price Range (₹)</label>
                            @if(request('min_price') || request('max_price'))
                                <button type="button" onclick="removeMultiParams(['min_price', 'max_price'])" class="text-[10px] font-bold text-teal-700 hover:underline">Clear</button>
                            @endif
                        </div>

                        <!-- Numeric inputs -->
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}" 
                                placeholder="Min ₹" 
                                class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500"
                            >
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}" 
                                placeholder="Max ₹" 
                                class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500"
                            >
                        </div>

                        <!-- Price preset chips -->
                        <div class="grid grid-cols-2 gap-1 mb-2 text-[10px]">
                            <button type="button" onclick="applyPricePreset(0, 1000)" class="px-2 py-1 rounded bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-semibold text-center transition-colors">
                                Under ₹1,000
                            </button>
                            <button type="button" onclick="applyPricePreset(1000, 5000)" class="px-2 py-1 rounded bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-semibold text-center transition-colors">
                                ₹1k – ₹5k
                            </button>
                            <button type="button" onclick="applyPricePreset(5000, 20000)" class="px-2 py-1 rounded bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-semibold text-center transition-colors">
                                ₹5k – ₹20k
                            </button>
                            <button type="button" onclick="applyPricePreset(20000, 50000)" class="px-2 py-1 rounded bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-semibold text-center transition-colors">
                                ₹20k – ₹50k
                            </button>
                        </div>

                        <button type="submit" class="w-full py-2 bg-[#002f34] hover:bg-[#004d55] text-white text-xs font-bold rounded-lg transition-colors shadow-xs">
                            Apply Price
                        </button>
                    </div>

                    <!-- 6. Multi-Select Condition Filter -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Item Condition</label>
                            @if(!empty($selectedConditions))
                                <button type="button" onclick="removeSingleParam('condition')" class="text-[10px] font-bold text-teal-700 hover:underline">Clear</button>
                            @endif
                        </div>
                        <div class="space-y-1.5 text-xs">
                            @foreach(['Brand New', 'Like New', 'Good', 'Fair'] as $cond)
                                <label class="flex items-center justify-between cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-700 hover:text-slate-900 group">
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            name="condition[]" 
                                            value="{{ $cond }}" 
                                            onchange="toggleArrayFilter('condition', '{{ $cond }}', this.checked)"
                                            {{ in_array($cond, $selectedConditions) ? 'checked' : '' }} 
                                            class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                                        >
                                        <span class="font-medium group-hover:text-slate-900">{{ $cond }}</span>
                                    </div>
                                    @if(in_array($cond, $selectedConditions))
                                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 7. Additional Filter Toggles -->
                    <div class="pt-2 border-t border-slate-100 space-y-2 text-xs">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Features</label>
                        
                        <!-- Negotiable -->
                        <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 text-slate-700">
                            <input 
                                type="checkbox" 
                                name="is_negotiable" 
                                value="1" 
                                onchange="toggleBooleanFilter('is_negotiable', this.checked)"
                                {{ request('is_negotiable') ? 'checked' : '' }} 
                                class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                            >
                            <span class="font-medium">Price Negotiable Only</span>
                        </label>

                        <!-- Featured Only -->
                        <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 text-slate-700">
                            <input 
                                type="checkbox" 
                                name="is_featured" 
                                value="1" 
                                onchange="toggleBooleanFilter('is_featured', this.checked)"
                                {{ request('is_featured') ? 'checked' : '' }} 
                                class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                            >
                            <span class="font-medium">★ Featured Ads Only</span>
                        </label>

                        <!-- With Photos -->
                        <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 text-slate-700">
                            <input 
                                type="checkbox" 
                                name="with_photos" 
                                value="1" 
                                onchange="toggleBooleanFilter('with_photos', this.checked)"
                                {{ request('with_photos') ? 'checked' : '' }} 
                                class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                            >
                            <span class="font-medium">With Photos Only</span>
                        </label>
                    </div>

                    <!-- 8. Date Posted -->
                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Posted Within</label>
                        <select name="posted_within" onchange="applySingleFilter('posted_within', this.value)" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 text-slate-800 font-medium cursor-pointer">
                            <option value="">Anytime</option>
                            <option value="24h" {{ request('posted_within') === '24h' || request('posted_within') === 'today' ? 'selected' : '' }}>Last 24 Hours</option>
                            <option value="7d" {{ request('posted_within') === '7d' || request('posted_within') === 'week' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30d" {{ request('posted_within') === '30d' || request('posted_within') === 'month' ? 'selected' : '' }}>Last 30 Days</option>
                        </select>
                    </div>

                    <!-- Apply All Submit Button -->
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md">
                        Apply All Filters
                    </button>
                </form>

            </div>
        </aside>

        <!-- Listings Results Grid -->
        <main class="lg:col-span-3 space-y-6">
            
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($listings as $listing)
                        <x-listing-card :listing="$listing" />
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-6">
                    {{ $listings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center max-w-lg mx-auto shadow-xs">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">No Matching Listings Found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">
                        We couldn't find any listings matching your combination of filters. Try removing some filters or broadening your search keywords.
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-md">
                            Clear All Filters
                        </a>
                        @if(request('search'))
                            <button type="button" onclick="removeSingleParam('search')" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                                Clear Keyword
                            </button>
                        @endif
                    </div>
                </div>
            @endif

        </main>

    </div>

</div>

<!-- Mobile Filter Drawer / Modal -->
<div id="mobile-filter-drawer" class="fixed inset-0 z-50 overflow-hidden hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div onclick="toggleMobileFilterDrawer(false)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-in fade-in duration-200"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between overflow-y-auto">
            
            <!-- Drawer Header -->
            <div class="p-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wider">Filter Marketplace</h2>
                </div>
                <button type="button" onclick="toggleMobileFilterDrawer(false)" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Drawer Body -->
            <form action="{{ route('listings.index') }}" method="GET" class="p-5 space-y-6 flex-1">
                <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                <!-- Mobile Search Input -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Search Keyword</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="e.g. iPhone, Toyota, Sofa..." 
                        class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium text-slate-800"
                    >
                </div>

                <!-- Mobile Listing Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Listing Type</label>
                    <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold text-center">
                        <label class="cursor-pointer py-1.5 rounded-lg {{ empty($currentType) ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600' }}">
                            <input type="radio" name="type" value="" {{ empty($currentType) ? 'checked' : '' }} class="hidden">
                            All
                        </label>
                        <label class="cursor-pointer py-1.5 rounded-lg {{ $currentType === 'product' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600' }}">
                            <input type="radio" name="type" value="product" {{ $currentType === 'product' ? 'checked' : '' }} class="hidden">
                            Products
                        </label>
                        <label class="cursor-pointer py-1.5 rounded-lg {{ $currentType === 'service' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600' }}">
                            <input type="radio" name="type" value="service" {{ $currentType === 'service' ? 'checked' : '' }} class="hidden">
                            Services
                        </label>
                    </div>
                </div>

                <!-- Mobile Category -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category</label>
                    <select name="category" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium text-slate-800">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $currentCategorySlug === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile City -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">City</label>
                    <select name="city" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium text-slate-800">
                        <option value="">All India</option>
                        @foreach($popularCities as $c)
                            <option value="{{ $c->slug }}" {{ $currentCitySlug === $c->slug ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Price Range -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Price Range (₹)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                    </div>
                </div>

                <!-- Mobile Conditions -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Item Condition</label>
                    <div class="space-y-2 text-xs">
                        @foreach(['Brand New', 'Like New', 'Good', 'Fair'] as $cond)
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="condition[]" value="{{ $cond }}" {{ in_array($cond, $selectedConditions) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600">
                                <span>{{ $cond }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Features -->
                <div class="space-y-2 text-xs pt-3 border-t border-slate-100">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_negotiable" value="1" {{ request('is_negotiable') ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600">
                        <span>Price Negotiable Only</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ request('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600">
                        <span>Featured Ads Only</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="with_photos" value="1" {{ request('with_photos') ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600">
                        <span>With Photos Only</span>
                    </label>
                </div>

                <div class="pt-4 sticky bottom-0 bg-white pb-6">
                    <button type="submit" class="w-full py-3.5 bg-[#002f34] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg">
                        Apply Filters
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    /**
     * Helper to navigate to the listings index route while maintaining existing search params.
     */
    function navigateWithParams(url) {
        window.location.href = url.toString();
    }

    /**
     * Apply or update a single filter parameter on the listings page.
     */
    function applySingleFilter(key, value, removeKeys = []) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        // Copy all existing params
        for (const [k, v] of currentParams.entries()) {
            if (k !== key && !removeKeys.includes(k) && k !== 'page' && !k.startsWith('condition[')) {
                url.searchParams.append(k, v);
            }
        }

        // Copy array params like condition[]
        const conditionParams = currentParams.getAll('condition[]');
        const conditionSingle = currentParams.getAll('condition');
        const conditions = [...new Set([...conditionParams, ...conditionSingle])];
        if (key !== 'condition' && !removeKeys.includes('condition')) {
            conditions.forEach(c => {
                if (c) url.searchParams.append('condition[]', c);
            });
        }

        // Set or remove the target param
        if (value !== '' && value !== null && value !== undefined) {
            url.searchParams.set(key, value);
        }

        navigateWithParams(url);
    }

    /**
     * Remove a single parameter from current query string.
     */
    function removeSingleParam(paramKey) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        for (const [k, v] of currentParams.entries()) {
            if (k !== paramKey && k !== 'page' && k !== paramKey + '[]') {
                url.searchParams.append(k, v);
            }
        }

        navigateWithParams(url);
    }

    /**
     * Remove multiple parameters from query string.
     */
    function removeMultiParams(paramKeys) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        for (const [k, v] of currentParams.entries()) {
            if (!paramKeys.includes(k) && !paramKeys.some(pk => k === pk + '[]') && k !== 'page') {
                url.searchParams.append(k, v);
            }
        }

        navigateWithParams(url);
    }

    /**
     * Remove a specific value from an array query parameter (e.g., condition[]).
     */
    function removeArrayParam(paramName, valueToRemove) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        // Copy non-condition params
        for (const [k, v] of currentParams.entries()) {
            if (k !== paramName && k !== paramName + '[]' && k !== 'page') {
                url.searchParams.append(k, v);
            }
        }

        // Handle array values
        const currentValues = [...new Set([...currentParams.getAll(paramName + '[]'), ...currentParams.getAll(paramName)])];
        currentValues.forEach(val => {
            if (val && val !== valueToRemove) {
                url.searchParams.append(paramName + '[]', val);
            }
        });

        navigateWithParams(url);
    }

    /**
     * Toggle a checkbox for array parameters (e.g. condition[]).
     */
    function toggleArrayFilter(paramName, value, isChecked) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        for (const [k, v] of currentParams.entries()) {
            if (k !== paramName && k !== paramName + '[]' && k !== 'page') {
                url.searchParams.append(k, v);
            }
        }

        let values = [...new Set([...currentParams.getAll(paramName + '[]'), ...currentParams.getAll(paramName)])];
        if (isChecked) {
            if (!values.includes(value)) {
                values.push(value);
            }
        } else {
            values = values.filter(v => v !== value);
        }

        values.forEach(val => {
            if (val) url.searchParams.append(paramName + '[]', val);
        });

        navigateWithParams(url);
    }

    /**
     * Toggle a boolean filter (negotiable, featured, with_photos).
     */
    function toggleBooleanFilter(paramName, isChecked) {
        if (isChecked) {
            applySingleFilter(paramName, '1');
        } else {
            removeSingleParam(paramName);
        }
    }

    /**
     * Set quick price preset.
     */
    function applyPricePreset(min, max) {
        const url = new URL('{{ route('listings.index') }}', window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        for (const [k, v] of currentParams.entries()) {
            if (k !== 'min_price' && k !== 'max_price' && k !== 'page') {
                url.searchParams.append(k, v);
            }
        }

        if (min !== null && min !== undefined) url.searchParams.set('min_price', min);
        if (max !== null && max !== undefined) url.searchParams.set('max_price', max);

        navigateWithParams(url);
    }

    /**
     * Apply sorting option.
     */
    function applySort(sortValue) {
        applySingleFilter('sort', sortValue);
    }

    /**
     * Toggle Mobile Filter Drawer.
     */
    function toggleMobileFilterDrawer(show) {
        const drawer = document.getElementById('mobile-filter-drawer');
        if (!drawer) return;
        if (show) {
            drawer.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            drawer.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
@endpush
@endsection
