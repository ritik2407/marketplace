<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Marketplace Control Center</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full flex text-slate-800 antialiased font-sans">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-[#001f22] text-slate-300 flex flex-col shrink-0 border-r border-slate-800 hidden md:flex">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-800/80 bg-[#001719]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-400 to-emerald-300 flex items-center justify-center text-slate-950 font-black text-lg shadow-sm">
                M
            </div>
            <div>
                <span class="font-extrabold text-white text-base tracking-tight block">MARKETPLACE</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-teal-400 block">ADMIN PORTAL</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <div class="pt-4 pb-1 text-[10px] uppercase font-bold text-slate-500 tracking-wider px-3">
                Content & Ads
            </div>

            <a href="{{ route('admin.listings.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.listings*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                All Listings
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Categories & Subs
            </a>

            <a href="{{ route('admin.locations.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.locations*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Cities & Locations
            </a>

            <div class="pt-4 pb-1 text-[10px] uppercase font-bold text-slate-500 tracking-wider px-3">
                Users & Inquiries
            </div>

            <a href="{{ route('admin.users.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.users*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users & Sellers
            </a>

            <a href="{{ route('admin.inquiries.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.inquiries*') ? 'bg-teal-500/20 text-teal-300 font-bold border border-teal-500/30' : 'hover:bg-slate-800/60 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                Buyer Inquiries
            </a>

            <div class="pt-6 border-t border-slate-800/80 my-2"></div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Visit Public Marketplace ↗
            </a>

        </nav>

        <!-- Admin Profile Bottom Footer -->
        <div class="p-4 border-t border-slate-800 bg-[#001719] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-teal-400">
                <div class="overflow-hidden">
                    <span class="text-xs font-bold text-white block truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-teal-400 block font-semibold">Super Admin</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                <!-- Mobile Navigation Toggle -->
                <a href="{{ route('admin.dashboard') }}" class="md:hidden font-extrabold text-[#002f34] text-lg">
                    ADMIN
                </a>
                <h2 class="text-lg font-black text-slate-900 tracking-tight hidden sm:block">
                    @yield('header_title', 'Marketplace Control Center')
                </h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <span>Front Store</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                <a href="{{ route('listings.create') }}" class="px-4 py-2 rounded-xl bg-[#002f34] hover:bg-[#00474e] text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-teal-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    <span>Create Ad</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-6">
            @if(session('success'))
                <div class="p-4 bg-teal-50 border border-teal-200 text-teal-900 rounded-2xl text-xs font-semibold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-teal-600 font-bold ml-4">✕</button>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs font-semibold flex items-center justify-between">
                    <span>{{ session('error') ?? $errors->first() }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 font-bold ml-4">✕</button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
