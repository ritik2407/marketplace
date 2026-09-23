@props(['listing'])

<div class="bg-white rounded-xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-200 flex flex-col group relative">
    
    <!-- Image Container with Badges -->
    <a href="{{ route('listings.show', $listing->slug) }}" class="relative block w-full aspect-[4/3] bg-slate-100 overflow-hidden">
        <img 
            src="{{ $listing->primary_image_url }}" 
            alt="{{ $listing->title }}" 
            loading="lazy"
            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
        >

        <!-- Top Badges -->
        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5 z-10">
            @if($listing->is_featured)
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-400 text-slate-900 shadow-xs">
                    ★ FEATURED
                </span>
            @endif

            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $listing->type === 'service' ? 'bg-indigo-600 text-white' : 'bg-[#002f34] text-white' }} shadow-xs">
                {{ $listing->type === 'service' ? 'SERVICE' : 'PRODUCT' }}
            </span>
        </div>

        @if($listing->condition)
            <div class="absolute bottom-2.5 left-2.5 z-10">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-white/90 backdrop-blur-xs text-slate-800 shadow-xs">
                    {{ $listing->condition }}
                </span>
            </div>
        @endif
    </a>

    <!-- Favorite Button -->
    <form action="{{ route('listings.favorite', $listing->id) }}" method="POST" class="absolute top-2.5 right-2.5 z-20">
        @csrf
        <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs hover:bg-white flex items-center justify-center text-slate-600 hover:text-rose-500 shadow-sm transition-colors" title="Save ad">
            <svg class="w-4 h-4 {{ Auth::check() && Auth::user()->favorites->contains('listing_id', $listing->id) ? 'fill-rose-500 text-rose-500' : 'fill-none text-slate-600' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
            </svg>
        </button>
    </form>

    <!-- Card Content -->
    <div class="p-3.5 flex-1 flex flex-col justify-between">
        <div>
            <!-- Price and Negotiable Flag -->
            <div class="flex items-baseline justify-between gap-2 mb-1">
                <span class="text-lg font-black text-slate-900 tracking-tight">
                    {{ $listing->formatted_price }}
                </span>
                @if($listing->is_negotiable)
                    <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Negotiable</span>
                @endif
            </div>

            <!-- Title -->
            <a href="{{ route('listings.show', $listing->slug) }}" class="block text-sm font-semibold text-slate-800 hover:text-teal-700 line-clamp-2 leading-snug mb-2 transition-colors">
                {{ $listing->title }}
            </a>
        </div>

        <!-- Location & Date Footer -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 mt-auto">
            <span class="truncate max-w-[130px] flex items-center gap-1" title="{{ $listing->location_string }}">
                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $listing->location_string }}
            </span>
            <span class="shrink-0 text-slate-400">{{ $listing->created_at->diffForHumans(null, true) }} ago</span>
        </div>

    </div>

</div>
