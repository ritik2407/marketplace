@extends('layouts.app')

@section('title', 'Edit Ad: ' . $listing->title . ' | Marketplace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="text-center mb-8">
        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-800">
            Edit Listing
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">
            UPDATE YOUR AD
        </h1>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Status & Type -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight">1. Listing Status & Type</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Listing Status</label>
                    <select name="status" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="active" {{ $listing->status === 'active' ? 'selected' : '' }}>🟢 Active (Visible to buyers)</option>
                        <option value="sold" {{ $listing->status === 'sold' ? 'selected' : '' }}>🔴 Sold Out</option>
                        <option value="inactive" {{ $listing->status === 'inactive' ? 'selected' : '' }}>⚪ Inactive / Hidden</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Type</label>
                    <select name="type" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="product" {{ $listing->type === 'product' ? 'selected' : '' }}>Physical Product</option>
                        <option value="service" {{ $listing->type === 'service' ? 'selected' : '' }}>Professional Service</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category & Subcategory -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight">2. Category</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category *</label>
                    <select id="category_select" name="category_id" required onchange="loadSubcategories(this.value)" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $listing->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subcategory</label>
                    <select id="subcategory_select" name="subcategory_id" class="w-full text-sm font-medium px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Select Subcategory --</option>
                        @if($listing->category)
                            @foreach($listing->category->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ $listing->subcategory_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <!-- Title, Price, Description -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
            <h2 class="text-lg font-black text-slate-900 tracking-tight">3. Listing Content</h2>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $listing->title) }}" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Price (₹) *</label>
                    <input type="number" name="price" value="{{ old('price', (int)$listing->price) }}" required min="0" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-bold">
                </div>

                <div class="pt-6 sm:pt-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <input type="checkbox" name="is_negotiable" value="1" {{ $listing->is_negotiable ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-bold text-slate-700">Negotiable</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Condition</label>
                    <select name="condition" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                        <option value="Brand New" {{ $listing->condition === 'Brand New' ? 'selected' : '' }}>Brand New</option>
                        <option value="Like New" {{ $listing->condition === 'Like New' ? 'selected' : '' }}>Like New</option>
                        <option value="Good" {{ $listing->condition === 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Fair" {{ $listing->condition === 'Fair' ? 'selected' : '' }}>Fair</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" rows="5" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-normal leading-relaxed">{{ old('description', $listing->description) }}</textarea>
            </div>
        </div>

        <!-- Location -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight">4. Location</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Country</label>
                    <select name="country_id" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $listing->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">State *</label>
                    <select name="state_id" id="state_select" required onchange="loadCities(this.value)" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $listing->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">City *</label>
                    <select name="city_id" id="city_select" required onchange="loadAreas(this.value)" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($cities as $c)
                            <option value="{{ $c->id }}" {{ $listing->city_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Area</label>
                    <select name="area_id" id="area_select" class="w-full text-xs font-medium px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="">-- Select Area --</option>
                        @foreach($areas as $a)
                            <option value="{{ $a->id }}" {{ $listing->area_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Add More Images -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 tracking-tight">5. Photos</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                @foreach($listing->images as $img)
                    <div class="relative aspect-video rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ $img->url }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>

            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Additional Photos</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('listings.show', $listing->slug) }}" class="px-6 py-3.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-10 py-4 bg-teal-600 hover:bg-teal-700 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl transition-all">
                Save Changes
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    async function loadSubcategories(categoryId) {
        const subSelect = document.getElementById('subcategory_select');
        subSelect.innerHTML = '<option value="">Loading...</option>';
        if (!categoryId) return;
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

    async function loadCities(stateId) {
        const citySelect = document.getElementById('city_select');
        citySelect.innerHTML = '<option value="">Loading...</option>';
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
</script>
@endpush
@endsection
