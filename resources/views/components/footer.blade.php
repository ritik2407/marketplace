@php
    $footerCategories = \App\Models\Category::with('subcategories')->orderBy('order')->take(4)->get();
    $footerCities = \App\Models\City::where('is_popular', true)->take(6)->get();
@endphp

<footer class="bg-[#002f34] text-slate-300 pt-12 pb-8 mt-16 border-t border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Grid Links -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 pb-12 border-b border-slate-700/60">
            
            <!-- Column 1: Popular Locations -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">POPULAR LOCATIONS</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    @foreach($footerCities as $fc)
                        <li>
                            <a href="{{ route('city.show', $fc->slug) }}" class="hover:text-teal-300 transition-colors">
                                {{ $fc->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 2: Trending Categories -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">TRENDING CATEGORIES</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    @foreach($footerCategories as $fcat)
                        <li>
                            <a href="{{ route('category.show', $fcat->slug) }}" class="hover:text-teal-300 transition-colors">
                                {{ $fcat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 3: About Marketplace -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">ABOUT US</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="#" class="hover:text-teal-300 transition-colors">About Marketplace Group</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Marketplace People</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Waah Jobs</a></li>
                </ul>
            </div>

            <!-- Column 4: OLX / Support & Safety -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">HELP & SAFETY</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Help Center & FAQ</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Safety Tips for Buyers</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Legal & Privacy information</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Vulnerability Disclosure</a></li>
                </ul>
            </div>

            <!-- Column 5: Brand & Sell CTA -->
            <div class="col-span-2 md:col-span-4 lg:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-teal-400 to-emerald-300 flex items-center justify-center font-bold text-slate-900 text-sm">
                        M
                    </div>
                    <span class="font-extrabold text-white text-lg tracking-tight">MARKETPLACE</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    The simplest and most secure way to buy and sell products and hire services near you.
                </p>
                <a href="{{ route('listings.create') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-lg bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                    + Post Free Ad Now
                </a>
            </div>

        </div>

        <!-- Copyright Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
            <p>© {{ date('Y') }} Marketplace Application. All rights reserved.</p>
            <div class="flex items-center space-x-6">
                <span>Free Classifieds in India</span>
                <span>•</span>
                <span>Products & Services</span>
            </div>
        </div>

    </div>
</footer>
