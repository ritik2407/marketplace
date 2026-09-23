@php
    $navCategories = \App\Models\Category::with('subcategories')->orderBy('order')->take(8)->get();
    $navCities = \App\Models\City::where('is_popular', true)->orderBy('name')->take(6)->get();
@endphp

<header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
    <!-- Top Bar with Location and Categories Quick Links -->
    <div class="bg-[#002f34] text-white text-xs py-1.5 px-4 hidden md:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <span class="text-teal-300 font-medium flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    Popular Cities:
                </span>
                @php
                    $currentCitySlug = request('city')
                        ?? (request()->routeIs('city.show') || request()->routeIs('city.category.show') ? request()->route('city_slug') : null)
                        ?? ($selectedCity->slug ?? null);
                @endphp
                @foreach($navCities as $c)
                    @php
                        $isNavCityActive = ($currentCitySlug === $c->slug) || (is_array(request('city')) && in_array($c->slug, request('city')));
                    @endphp
                    <a href="{{ route('city.show', $c->slug) }}" class="hover:text-teal-300 transition-colors {{ $isNavCityActive ? 'text-teal-300 font-extrabold underline decoration-teal-400 decoration-2 underline-offset-4' : 'text-slate-300' }}">
                        {{ $c->name }}
                    </a>
                @endforeach
            </div>
            <div class="flex items-center space-x-4 text-slate-300">
                <span class="text-xs">India's Trusted Marketplace</span>
                <span>•</span>
                <a href="{{ route('listings.index', ['type' => 'product']) }}" class="hover:text-white {{ request('type') === 'product' ? 'text-teal-300 font-bold' : '' }}">Products</a>
                <span>•</span>
                <a href="{{ route('listings.index', ['type' => 'service']) }}" class="hover:text-white {{ request('type') === 'service' ? 'text-teal-300 font-bold' : '' }}">Services</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#002f34] via-[#007a82] to-[#23e5db] flex items-center justify-center shadow-md shadow-teal-900/10 group-hover:scale-105 transition-transform">
                    <span class="text-white font-black text-xl tracking-tighter">M</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-2xl tracking-tight text-[#002f34] leading-none">
                        MARKET<span class="text-teal-600">PLACE</span>
                    </span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Buy & Sell Local</span>
                </div>
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="hidden lg:flex flex-1 max-w-2xl items-center">
                <form action="{{ route('listings.index') }}" method="GET" class="flex w-full border-2 border-[#002f34] rounded-lg overflow-hidden bg-white shadow-xs focus-within:ring-2 focus-within:ring-teal-500">
                    <!-- City Selector in Search -->
                    <div class="relative min-w-[140px] border-r border-slate-200 bg-slate-50 flex items-center">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <select name="city" class="w-full pl-9 pr-7 py-2.5 bg-transparent text-xs font-semibold text-slate-700 focus:outline-none appearance-none cursor-pointer">
                            <option value="">All India</option>
                            @foreach($navCities as $c)
                                <option value="{{ $c->slug }}" {{ request('city') === $c->slug ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-3 h-3 text-slate-400 absolute right-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    <!-- Search Input -->
                    <div class="relative flex-1 flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find Cars, Mobile Phones, Laptops, AC Repair, Services..." class="w-full px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-[#002f34] hover:bg-[#004d55] text-white px-6 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <!-- Right Actions: Auth, Dashboard, Post Ad Button -->
            <div class="flex items-center gap-3">
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" id="user-menu-container">
                        <button type="button" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-slate-100 transition-colors focus:outline-none">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-teal-500">
                            <span class="hidden md:block text-sm font-semibold text-slate-700 max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-500 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Signed in as</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-teal-800 bg-teal-50 hover:bg-teal-100 font-bold">
                                    <svg class="w-4 h-4 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Admin Control Panel
                                </a>
                            @endif

                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-teal-50 hover:text-teal-700 font-medium">
                                <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                My Ads & Dashboard
                            </a>

                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-teal-50 hover:text-teal-700 font-medium">
                                <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Settings
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                    <svg class="w-4 h-4 mr-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login / Register Buttons -->
                    <a href="{{ route('login') }}" class="text-sm font-bold text-[#002f34] hover:text-teal-600 px-3 py-2 underline decoration-2 underline-offset-4">
                        Login
                    </a>
                @endauth

                <!-- SELL / POST AD BUTTON (OLX Style High Contrast) -->
                <a href="{{ route('listings.create') }}" class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-black text-slate-900 rounded-full group bg-gradient-to-r from-amber-400 via-teal-400 to-sky-400 group-hover:from-amber-400 group-hover:to-teal-500 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    <span class="relative px-5 py-2 transition-all ease-in duration-75 bg-white rounded-full group-hover:bg-opacity-0 group-hover:text-white flex items-center gap-1.5 font-bold uppercase tracking-wider text-xs">
                        <svg class="w-4 h-4 text-teal-600 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                        + SELL
                    </span>
                </a>
            </div>
        </div>

        <!-- Category Bar Sub-Menu -->
        @php
            $currentCategorySlug = request('category') 
                ?? (request()->routeIs('category.show') || request()->routeIs('subcategory.show') || request()->routeIs('city.category.show') ? request()->route('category_slug') : null)
                ?? ($selectedCategory->slug ?? null);
        @endphp
        <div class="flex items-center justify-between overflow-x-auto py-2.5 border-t border-slate-100 text-sm no-scrollbar">
            <div class="flex items-center space-x-6 min-w-max">
                <a href="{{ route('listings.index') }}" class="font-bold flex items-center gap-1 transition-colors {{ empty($currentCategorySlug) ? 'text-teal-700 font-extrabold border-b-2 border-teal-700 pb-0.5' : 'text-slate-700 hover:text-teal-600' }}">
                    <span>ALL CATEGORIES</span>
                </a>

                @foreach($navCategories as $cat)
                    @php
                        $isNavCatActive = ($currentCategorySlug === $cat->slug) || (is_array(request('category')) && in_array($cat->slug, request('category')));
                    @endphp
                    <a href="{{ route('category.show', $cat->slug) }}" class="text-xs transition-colors py-0.5 {{ $isNavCatActive ? 'text-teal-700 font-extrabold border-b-2 border-teal-700' : 'text-slate-600 hover:text-teal-600 font-medium' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>
