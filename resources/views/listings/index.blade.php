@extends('layouts.app')

@section('title', $pageTitle . ' | Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center text-xs text-slate-500 mb-6 flex-wrap gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-teal-700 font-medium">Home</a>
        <span>/</span>

        @if(!empty($selectedCity))
            <a href="{{ route('city.show', $selectedCity->slug) }}" class="hover:text-teal-700 font-medium">{{ $selectedCity->name }}</a>
            <span>/</span>
        @endif

        @if(!empty($selectedCategory))
            <a href="{{ route('category.show', $selectedCategory->slug) }}" class="hover:text-teal-700 font-medium">{{ $selectedCategory->name }}</a>
            @if(!empty($selectedSubcategory))
                <span>/</span>
                <span class="text-slate-900 font-bold">{{ $selectedSubcategory->name }}</span>
            @endif
        @else
            <span class="text-slate-900 font-bold">All Listings</span>
        @endif
    </nav>

    <!-- Page Title & Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ $pageTitle }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Showing <span class="font-bold text-slate-800">{{ $listings->total() }}</span> verified ads
                @if(request('city')) in <span class="font-semibold text-teal-700">{{ ucfirst(request('city')) }}</span>@endif
                @if(request('search')) matching "<span class="font-semibold text-teal-700">{{ request('search') }}</span>"@endif
            </p>
        </div>

        <!-- Quick Sort & Reset Filters -->
        <div class="flex items-center gap-3">
            @if(!empty(array_filter(request()->except('page'))))
                <a href="{{ route('listings.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Clear All Filters
                </a>
            @endif

            <div class="flex items-center gap-2">
                <label for="sort-select" class="text-xs font-semibold text-slate-500 shrink-0">Sort By:</label>
                <select id="sort-select" onchange="applyFilter('sort', this.value)" class="text-xs font-semibold bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newly Listed</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Layout: Sidebar Filters + Listings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1 space-y-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filters
                    </h3>
                </div>

                <!-- 1. Type Filter (All, Product, Service) -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Listing Type</h4>
                    <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold text-center">
                        <a href="{{ request()->fullUrlWithQuery(['type' => null, 'page' => null]) }}" class="py-1.5 rounded-lg transition-colors {{ !request('type') ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                            All
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['type' => 'product', 'page' => null]) }}" class="py-1.5 rounded-lg transition-colors {{ request('type') === 'product' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                            Product
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['type' => 'service', 'page' => null]) }}" class="py-1.5 rounded-lg transition-colors {{ request('type') === 'service' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                            Service
                        </a>
                    </div>
                </div>

                <!-- 2. Categories & Subcategories Accordion -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Categories</h4>
                    <div class="space-y-1 text-xs max-h-64 overflow-y-auto pr-1">
                        <a href="{{ route('listings.index', request()->except(['category', 'subcategory', 'page'])) }}" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition-colors {{ !request('category') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-700' }}">
                            <span>All Categories</span>
                        </a>

                        @foreach($categories as $cat)
                            <div>
                                <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition-colors {{ request('category') === $cat->slug ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-700' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal">{{ $cat->subcategories->count() }}</span>
                                </a>

                                <!-- Show Subcategories if this category is selected -->
                                @if(request('category') === $cat->slug)
                                    <div class="ml-4 pl-2 border-l border-teal-200 my-1 space-y-1">
                                        @foreach($cat->subcategories as $sub)
                                            <a href="{{ route('subcategory.show', ['category_slug' => $cat->slug, 'subcategory_slug' => $sub->slug]) }}" class="block px-2 py-1 rounded text-[11px] hover:text-teal-700 transition-colors {{ request('subcategory') === $sub->slug ? 'text-teal-700 font-bold bg-teal-100/50' : 'text-slate-600' }}">
                                                • {{ $sub->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. City / Location Filter -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Location / City</h4>
                    <div class="space-y-1 text-xs max-h-52 overflow-y-auto pr-1">
                        <a href="{{ request()->fullUrlWithQuery(['city' => null, 'page' => null]) }}" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition-colors {{ !request('city') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-700' }}">
                            <span>All India</span>
                        </a>
                        @foreach($popularCities as $c)
                            <a href="{{ request()->fullUrlWithQuery(['city' => $c->slug, 'page' => null]) }}" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition-colors {{ request('city') === $c->slug ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-700' }}">
                                <span>{{ $c->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Price Range Filter -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Price Range (₹)</h4>
                    <form action="{{ url()->current() }}" method="GET" class="space-y-2">
                        <!-- Preserve other query params -->
                        @foreach(request()->except(['min_price', 'max_price', 'page']) as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $subV)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach

                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                        </div>
                        <button type="submit" class="w-full py-2 bg-[#002f34] hover:bg-[#004d55] text-white text-xs font-bold rounded-lg transition-colors">
                            Apply Price
                        </button>
                    </form>
                </div>

                <!-- 5. Condition Filter -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Item Condition</h4>
                    <div class="space-y-1.5 text-xs">
                        @foreach(['Brand New', 'Like New', 'Good', 'Fair'] as $cond)
                            <label class="flex items-center gap-2 cursor-pointer text-slate-700 hover:text-slate-900">
                                <input type="checkbox" onchange="applyCondition('{{ $cond }}', this.checked)" {{ request('condition') === $cond ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                                <span>{{ $cond }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </aside>

        <!-- Listings Grid -->
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
                <div class="bg-white rounded-3xl border border-slate-200/90 p-12 text-center max-w-lg mx-auto shadow-xs">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-1">No Listings Found</h3>
                    <p class="text-xs text-slate-500 mb-6">
                        We couldn't find any ads matching your current search and filter criteria. Try adjusting filters or searching for something else.
                    </p>
                    <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-teal-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-teal-700 transition-colors">
                        Reset All Filters
                    </a>
                </div>
            @endif

        </main>

    </div>

</div>

@push('scripts')
<script>
    function applyFilter(key, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function applyCondition(condition, isChecked) {
        const url = new URL(window.location.href);
        if (isChecked) {
            url.searchParams.set('condition', condition);
        } else {
            url.searchParams.delete('condition');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush
@endsection
