@extends('layouts.app')

@section('title', 'Post Your Free Ad | Marketplace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="text-center mb-8">
        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-teal-100 text-teal-800">
            Free Classifieds Listing
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">
            POST YOUR AD
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">
            Reach thousands of buyers in your city. It takes less than 2 minutes.
        </p>
    </div>

    <!-- Main Creation Form -->
    <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- STEP 1: TYPE SELECTION (Product vs Service) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">1</span>
                What are you listing?
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all border-teal-500 bg-teal-50/30" id="type-card-product">
                    <input type="radio" name="type" value="product" checked onchange="toggleType('product')" class="text-teal-600 focus:ring-teal-500">
                    <div class="ml-3">
                        <span class="block text-sm font-bold text-slate-900">Physical Product</span>
                        <span class="block text-xs text-slate-500">Cars, Mobiles, Electronics, Furniture, Real Estate...</span>
                    </div>
                </label>

                <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all border-slate-200 hover:border-slate-300" id="type-card-service">
                    <input type="radio" name="type" value="service" onchange="toggleType('service')" class="text-teal-600 focus:ring-teal-500">
                    <div class="ml-3">
                        <span class="block text-sm font-bold text-slate-900">Service / Freelance</span>
                        <span class="block text-xs text-slate-500">Home Cleaning, AC Repair, Tuition, Development, Events...</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- STEP 2: CATEGORY & SUBCATEGORY -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">2</span>
                Choose Category & Subcategory
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category *</label>
                    <select id="category_select" name="category_id" required onchange="loadSubcategories(this.value)" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select Main Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subcategory (Optional)</label>
                    <select id="subcategory_select" name="subcategory_id" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select Subcategory --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- STEP 3: DETAILS & PRICING -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">3</span>
                Listing Details & Pricing
            </h2>

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ad Title / Name *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Apple iPhone 15 Pro Max 256GB Natural Titanium (Mint Condition)" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                <span class="text-[11px] text-slate-400 mt-1 block">Mention key brand, model, features to attract more buyers</span>
            </div>

            <!-- Price & Negotiable & Condition -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Price (₹ INR) *</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 text-slate-500 font-bold text-sm">₹</span>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1" placeholder="25000" class="w-full text-sm pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-bold">
                    </div>
                </div>

                <div class="sm:col-span-1 pt-6 sm:pt-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <input type="checkbox" name="is_negotiable" value="1" {{ old('is_negotiable') ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-bold text-slate-700">Price is Negotiable</span>
                    </label>
                </div>

                <div class="sm:col-span-1" id="condition-field">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Condition</label>
                    <select name="condition" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                        <option value="Brand New">Brand New</option>
                        <option value="Like New" selected>Like New</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description / Detail *</label>
                <textarea name="description" rows="5" required placeholder="Include condition, reason for selling, age of item, warranty status, and what is included..." class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-normal leading-relaxed">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- STEP 4: LOCATION HIERARCHY (Country -> State -> City -> Area) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">4</span>
                Confirm Your Location
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Country -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Country</label>
                    <select name="country_id" id="country_select" onchange="loadStates(this.value)" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- State -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">State *</label>
                    <select name="state_id" id="state_select" required onchange="loadCities(this.value)" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select State --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">City *</label>
                    <select name="city_id" id="city_select" required onchange="loadAreas(this.value)" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select City --</option>
                    </select>
                </div>

                <!-- Area -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Area / Locality</label>
                    <select name="area_id" id="area_select" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select Area --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- STEP 5: UPLOAD PHOTOS -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">5</span>
                Upload Photos
            </h2>

            <div class="border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-6 text-center cursor-pointer bg-slate-50/50 transition-colors relative" onclick="document.getElementById('images_input').click()">
                <input type="file" id="images_input" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                
                <div class="space-y-2">
                    <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-800">Click to upload photos or drag and drop</p>
                    <p class="text-xs text-slate-400">Upload up to 8 photos (PNG, JPG, JPEG, WEBP up to 5MB each)</p>
                </div>
            </div>

            <!-- Live Image Previews -->
            <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-4 gap-3 hidden pt-2"></div>
        </div>

        <!-- STEP 6: CONTACT INFORMATION -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#002f34] text-white text-xs flex items-center justify-center font-bold">6</span>
                Review Your Contact Info
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '+91 98765 43210') }}" class="w-full text-xs px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">WhatsApp Number</label>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp', Auth::user()->phone ?? '+91 98765 43210') }}" class="w-full text-xs px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full text-xs px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                </div>
            </div>
        </div>

        <!-- Submit Button CTA -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('home') }}" class="px-6 py-3.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-slate-950 font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <span>Publish Ad Now</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    function toggleType(type) {
        const productCard = document.getElementById('type-card-product');
        const serviceCard = document.getElementById('type-card-service');
        const conditionField = document.getElementById('condition-field');

        if (type === 'product') {
            productCard.classList.add('border-teal-500', 'bg-teal-50/30');
            productCard.classList.remove('border-slate-200');
            serviceCard.classList.remove('border-teal-500', 'bg-teal-50/30');
            serviceCard.classList.add('border-slate-200');
            if (conditionField) conditionField.style.display = 'block';
        } else {
            serviceCard.classList.add('border-teal-500', 'bg-teal-50/30');
            serviceCard.classList.remove('border-slate-200');
            productCard.classList.remove('border-teal-500', 'bg-teal-50/30');
            productCard.classList.add('border-slate-200');
            if (conditionField) conditionField.style.display = 'none';
        }
    }

    async function loadSubcategories(categoryId) {
        const subSelect = document.getElementById('subcategory_select');
        subSelect.innerHTML = '<option value="">Loading...</option>';
        if (!categoryId) {
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
            return;
        }

        try {
            const res = await fetch(`{{ route('cascade.subcategories') }}?category_id=${categoryId}`);
            const data = await res.json();
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                subSelect.appendChild(opt);
            });
        } catch (e) {
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
        }
    }

    async function loadStates(countryId) {
        const stateSelect = document.getElementById('state_select');
        stateSelect.innerHTML = '<option value="">Loading...</option>';
        try {
            const res = await fetch(`{{ route('cascade.states') }}?country_id=${countryId}`);
            const data = await res.json();
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                stateSelect.appendChild(opt);
            });
        } catch (e) {
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
        }
    }

    async function loadCities(stateId) {
        const citySelect = document.getElementById('city_select');
        citySelect.innerHTML = '<option value="">Loading...</option>';
        document.getElementById('area_select').innerHTML = '<option value="">-- Select Area --</option>';
        if (!stateId) return;

        try {
            const res = await fetch(`{{ route('cascade.cities') }}?state_id=${stateId}`);
            const data = await res.json();
            citySelect.innerHTML = '<option value="">-- Select City --</option>';
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                citySelect.appendChild(opt);
            });
        } catch (e) {
            citySelect.innerHTML = '<option value="">-- Select City --</option>';
        }
    }

    async function loadAreas(cityId) {
        const areaSelect = document.getElementById('area_select');
        areaSelect.innerHTML = '<option value="">Loading...</option>';
        if (!cityId) return;

        try {
            const res = await fetch(`{{ route('cascade.areas') }}?city_id=${cityId}`);
            const data = await res.json();
            areaSelect.innerHTML = '<option value="">-- Select Area --</option>';
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                areaSelect.appendChild(opt);
            });
        } catch (e) {
            areaSelect.innerHTML = '<option value="">-- Select Area --</option>';
        }
    }

    function previewImages(input) {
        const container = document.getElementById('image-preview-container');
        container.innerHTML = '';
        if (input.files && input.files.length > 0) {
            container.classList.remove('hidden');
            Array.from(input.files).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-video rounded-xl overflow-hidden border border-slate-200 bg-slate-100';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <span class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-black/60 text-white">${idx === 0 ? 'Primary' : 'Photo ' + (idx + 1)}</span>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
