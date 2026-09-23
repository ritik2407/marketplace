@extends('layouts.app')

@section('title', $listing->title . ' | Marketplace')
@section('meta_description', Str::limit(strip_tags($listing->description), 150))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-500 flex-wrap gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-teal-700 font-medium">Home</a>
        <span>/</span>
        @if($listing->category)
            <a href="{{ route('category.show', $listing->category->slug) }}" class="hover:text-teal-700 font-medium">{{ $listing->category->name }}</a>
            <span>/</span>
        @endif
        @if($listing->city)
            <a href="{{ route('city.show', $listing->city->slug) }}" class="hover:text-teal-700 font-medium">{{ $listing->city->name }}</a>
            <span>/</span>
        @endif
        <span class="text-slate-800 font-bold truncate max-w-xs">{{ $listing->title }}</span>
    </nav>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 8 Columns: Gallery, Overview, Description, Specs -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Gallery Container -->
            <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs p-4 sm:p-6 space-y-4">
                <!-- Main High-Res Image View -->
                <div class="relative w-full aspect-[16/10] sm:aspect-[16/9] bg-slate-950 rounded-2xl overflow-hidden flex items-center justify-center">
                    <img 
                        id="main-gallery-img" 
                        src="{{ $listing->primary_image_url }}" 
                        alt="{{ $listing->title }}" 
                        class="w-full h-full object-contain max-h-[500px]"
                    >

                    <!-- Type Tag -->
                    <div class="absolute top-4 left-4 z-10 flex gap-2">
                        <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider {{ $listing->type === 'service' ? 'bg-indigo-600' : 'bg-[#002f34]' }} text-white shadow-md">
                            {{ $listing->type === 'service' ? 'SERVICE' : 'PRODUCT' }}
                        </span>
                        @if($listing->is_featured)
                            <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-amber-400 text-slate-900 shadow-md">
                                ★ FEATURED
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails Strip -->
                @if($listing->images->count() > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2 no-scrollbar">
                        @foreach($listing->images as $idx => $img)
                            <button 
                                type="button" 
                                onclick="document.getElementById('main-gallery-img').src = '{{ $img->url }}'; setActiveThumb(this);"
                                class="thumb-btn shrink-0 w-20 h-16 rounded-xl overflow-hidden border-2 {{ $idx === 0 ? 'border-teal-500 ring-2 ring-teal-200' : 'border-slate-200 opacity-70 hover:opacity-100' }} transition-all"
                            >
                                <img src="{{ $img->url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
                <h2 class="text-xl font-black text-slate-900 tracking-tight pb-3 border-b border-slate-100">
                    Description & Details
                </h2>
                <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-700 whitespace-pre-line">
                    {{ $listing->description }}
                </div>
            </div>

            <!-- Specifications & Location Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <h2 class="text-xl font-black text-slate-900 tracking-tight pb-3 border-b border-slate-100">
                    Product / Service Information
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-500 font-medium">Category</span>
                        <span class="font-bold text-slate-800">{{ $listing->category?->name ?? 'General' }}</span>
                    </div>

                    @if($listing->subcategory)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                            <span class="text-slate-500 font-medium">Subcategory</span>
                            <span class="font-bold text-slate-800">{{ $listing->subcategory->name }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-500 font-medium">Type</span>
                        <span class="font-bold text-slate-800 uppercase">{{ $listing->type }}</span>
                    </div>

                    @if($listing->condition)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                            <span class="text-slate-500 font-medium">Condition</span>
                            <span class="font-bold text-emerald-700">{{ $listing->condition }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-500 font-medium">Country</span>
                        <span class="font-bold text-slate-800">{{ $listing->country?->name ?? 'India' }}</span>
                    </div>

                    @if($listing->state)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                            <span class="text-slate-500 font-medium">State</span>
                            <span class="font-bold text-slate-800">{{ $listing->state->name }}</span>
                        </div>
                    @endif

                    @if($listing->city)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                            <span class="text-slate-500 font-medium">City</span>
                            <span class="font-bold text-slate-800">{{ $listing->city->name }}</span>
                        </div>
                    @endif

                    @if($listing->area)
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                            <span class="text-slate-500 font-medium">Area / Locality</span>
                            <span class="font-bold text-slate-800">{{ $listing->area->name }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-500 font-medium">Ad ID</span>
                        <span class="font-mono font-bold text-slate-600">#AD-{{ str_pad($listing->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-500 font-medium">Posted</span>
                        <span class="font-bold text-slate-800">{{ $listing->created_at->format('M d, Y') }} ({{ $listing->created_at->diffForHumans() }})</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 4 Columns: Price Card, Seller Info, Contact Actions, Safety -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Price & Title Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm space-y-4">
                <div class="flex items-baseline justify-between gap-2">
                    <span class="text-3xl font-black text-slate-950 tracking-tight">
                        {{ $listing->formatted_price }}
                    </span>
                    @if($listing->is_negotiable)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Negotiable
                        </span>
                    @endif
                </div>

                <h1 class="text-xl font-bold text-slate-900 leading-snug">
                    {{ $listing->title }}
                </h1>

                <div class="flex items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $listing->location_string }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        {{ $listing->views_count }} views
                    </span>
                </div>
            </div>

            <!-- Seller Card & Direct Contact -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ $listing->user->avatar_url }}" alt="{{ $listing->user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-teal-500">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="font-extrabold text-base text-slate-900">{{ $listing->user->name }}</h3>
                                <svg class="w-4 h-4 text-teal-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <p class="text-xs text-slate-500">Member since {{ $listing->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Action Buttons -->
                <div class="space-y-3">
                    
                    <!-- 1. Show Phone Number Button -->
                    @php
                        $phone = $listing->phone ?? $listing->user->phone ?? '+91 98765 43210';
                    @endphp
                    <div id="phone-box" class="w-full">
                        <button 
                            type="button" 
                            onclick="document.getElementById('phone-number-text').classList.remove('hidden'); document.getElementById('phone-mask-text').classList.add('hidden');"
                            class="w-full py-3.5 px-4 bg-[#002f34] hover:bg-[#004d55] text-white font-bold text-sm rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm"
                        >
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span id="phone-mask-text">Show Phone Number</span>
                            <span id="phone-number-text" class="hidden font-mono tracking-wider text-teal-300">{{ $phone }}</span>
                        </button>
                    </div>

                    <!-- 2. WhatsApp Direct Chat Button -->
                    @php
                        $waNumber = preg_replace('/[^0-9]/', '', $listing->whatsapp ?? $listing->phone ?? '919876543210');
                        $waMessage = urlencode("Hi, I am interested in your ad on Marketplace: " . $listing->title . " (" . route('listings.show', $listing->slug) . ")");
                    @endphp
                    <a 
                        href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" 
                        target="_blank" 
                        rel="noopener"
                        class="w-full py-3.5 px-4 bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-sm rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm"
                    >
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.316 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.818-.981z"/></svg>
                        <span>Chat on WhatsApp</span>
                    </a>

                </div>

                <!-- Send Message / Inquiry Form -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Send Inquiry to Seller</h4>
                    <form action="{{ route('listings.inquiry', $listing->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <input type="text" name="name" value="{{ Auth::check() ? Auth::user()->name : old('name') }}" placeholder="Your Name" required class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div>
                            <input type="email" name="email" value="{{ Auth::check() ? Auth::user()->email : old('email') }}" placeholder="Your Email Address" required class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div>
                            <input type="tel" name="phone" value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" placeholder="Your Phone Number (Optional)" class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div>
                            <textarea name="message" rows="3" placeholder="Is this still available? I am interested in discussing the price." required class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-teal-500"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Safety Tips Card -->
            <div class="bg-amber-50/70 border border-amber-200/80 rounded-3xl p-6 text-slate-800 space-y-3">
                <div class="flex items-center gap-2 text-amber-800 font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Safety Tips for Buyers
                </div>
                <ul class="text-xs space-y-1.5 text-slate-700 list-disc list-inside">
                    <li>Meet seller in a safe, public location</li>
                    <li>Check the item condition thoroughly before paying</li>
                    <li>Avoid sending advance payments or deposits</li>
                    <li>Beware of unrealistic below-market offers</li>
                </ul>
            </div>

        </div>

    </div>

    <!-- Related Listings Section -->
    @if($relatedListings->count() > 0)
        <div class="pt-8 border-t border-slate-200 space-y-6">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Similar Ads You May Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedListings as $related)
                    <x-listing-card :listing="$related" />
                @endforeach
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function setActiveThumb(el) {
        document.querySelectorAll('.thumb-btn').forEach(btn => {
            btn.classList.remove('border-teal-500', 'ring-2', 'ring-teal-200');
            btn.classList.add('border-slate-200', 'opacity-70');
        });
        el.classList.add('border-teal-500', 'ring-2', 'ring-teal-200');
        el.classList.remove('opacity-70');
    }
</script>
@endpush
@endsection
