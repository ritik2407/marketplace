@extends('admin.layouts.admin')

@section('title', 'Edit Listing #' . $listing->id)
@section('header_title', 'Admin Edit: ' . $listing->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.listings.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to All Listings
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200/60 px-3 py-1.5 rounded-xl hover:bg-teal-100 flex items-center gap-1 transition">
                <span>View Live Ad</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
            <form action="{{ route('admin.listings.destroy', $listing->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this listing and all its images?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 border border-red-200/60 px-3 py-1.5 rounded-xl hover:bg-red-100 transition">
                    Delete Listing
                </button>
            </form>
        </div>
    </div>

    <!-- Image Management Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">Ad Photos & Gallery Management</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-teal-50 text-teal-700 border border-teal-200/60">
                        {{ $listing->images->count() }} {{ Str::plural('Photo', $listing->images->count()) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Upload new images, set the primary cover thumbnail, or remove outdated photos.</p>
            </div>

            <!-- Upload Toggle Button -->
            <button type="button" onclick="document.getElementById('upload-images-section').classList.toggle('hidden')" class="self-start sm:self-auto inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Upload Photos</span>
            </button>
        </div>

        <!-- Add Photos Form Panel -->
        <div id="upload-images-section" class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80">
            <form action="{{ route('admin.listings.images.upload', $listing->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Images (JPEG, PNG, WebP up to 5MB each)</label>
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-6 text-center bg-white transition cursor-pointer" onclick="document.getElementById('admin_gallery_input').click()">
                        <input type="file" id="admin_gallery_input" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewGalleryUploads(this)">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-700">Click to browse or drop images here</span>
                            <span class="text-[11px] text-slate-400 mt-1">You can select multiple files at once</span>
                        </div>
                    </div>
                </div>

                <!-- Instant client preview container -->
                <div id="gallery-preview-container" class="hidden sm:grid-cols-4 md:grid-cols-6 gap-3 pt-2"></div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('upload-images-section').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-100 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                        Upload to Ad
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Images Grid -->
        @if($listing->images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($listing->images as $image)
                    <div class="group relative bg-slate-50 rounded-2xl border {{ $image->is_primary ? 'border-amber-400 ring-2 ring-amber-400/40' : 'border-slate-200' }} overflow-hidden flex flex-col justify-between transition hover:shadow-md">
                        <!-- Image Container -->
                        <div class="aspect-4/3 w-full bg-slate-100 overflow-hidden relative">
                            <img src="{{ $image->url }}" alt="Ad Photo #{{ $image->id }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                            <!-- Primary Badge -->
                            @if($image->is_primary)
                                <div class="absolute top-2 left-2 bg-amber-500 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1 backdrop-blur-xs">
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Primary Cover
                                </div>
                            @endif

                            <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] font-mono px-2 py-0.5 rounded-md backdrop-blur-xs">
                                #{{ $image->id }}
                            </span>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="p-2.5 bg-white border-t border-slate-100 flex items-center justify-between gap-1">
                            @if(! $image->is_primary)
                                <form action="{{ route('admin.images.primary', $image->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[11px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2.5 py-1 rounded-lg transition border border-amber-200/50 flex items-center gap-1 cursor-pointer" title="Set as main thumbnail">
                                        <span>★ Cover</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] font-bold text-amber-600 px-2 py-1">Cover Photo</span>
                            @endif

                            <!-- Delete Image Form -->
                            <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Delete this image permanently from the ad?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition border border-red-200/50 flex items-center gap-1 cursor-pointer" title="Delete image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200/70">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No Photos Attached</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">This listing doesn't have any images uploaded yet. Use the upload button above to add photos.</p>
            </div>
        @endif
    </div>

    <!-- Ad Details Form -->
    <form action="{{ route('admin.listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xs space-y-6 text-xs">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900">Listing Information</h2>
                <p class="text-xs text-slate-500">Edit listing title, category, pricing, condition and details.</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg">ID: #{{ $listing->id }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Title -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $listing->title) }}" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 font-semibold">
            </div>

            <!-- Price -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Price (₹) *</label>
                <input type="number" name="price" value="{{ old('price', (int)$listing->price) }}" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-teal-700">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Category -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Category *</label>
                <select name="category_id" id="admin_category_select" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $listing->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Type</label>
                <select name="type" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium">
                    <option value="product" {{ $listing->type === 'product' ? 'selected' : '' }}>Product</option>
                    <option value="service" {{ $listing->type === 'service' ? 'selected' : '' }}>Service</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold {{ $listing->status === 'active' ? 'text-emerald-700' : ($listing->status === 'sold' ? 'text-blue-700' : 'text-slate-500') }}">
                    <option value="active" {{ $listing->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="sold" {{ $listing->status === 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="inactive" {{ $listing->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Condition -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Condition</label>
                <select name="condition" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="Brand New" {{ $listing->condition === 'Brand New' ? 'selected' : '' }}>Brand New</option>
                    <option value="Like New" {{ $listing->condition === 'Like New' ? 'selected' : '' }}>Like New</option>
                    <option value="Good" {{ $listing->condition === 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Fair" {{ $listing->condition === 'Fair' ? 'selected' : '' }}>Fair</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                <input type="checkbox" name="is_featured" value="1" {{ $listing->is_featured ? 'checked' : '' }} class="rounded text-amber-500 focus:ring-amber-400">
                <span>★ Featured on Homepage</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                <input type="checkbox" name="is_negotiable" value="1" {{ $listing->is_negotiable ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                <span>Price is Negotiable</span>
            </label>
        </div>

        <!-- Description -->
        <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Description *</label>
            <textarea name="description" rows="6" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">{{ old('description', $listing->description) }}</textarea>
        </div>

        <!-- Seller info box -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ $listing->user->avatar_url }}" alt="{{ $listing->user->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-200">
                <div>
                    <span class="font-bold text-slate-900 block">Seller: {{ $listing->user->name }} ({{ $listing->user->email }})</span>
                    <span class="text-[11px] text-slate-500">Phone: {{ $listing->phone ?? $listing->user->phone ?? 'N/A' }} | Location: {{ $listing->location_string }}</span>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-slate-400 font-mono block">Posted: {{ $listing->created_at->format('M d, Y H:i') }}</span>
                <span class="text-[10px] text-slate-400 font-mono block">Views: {{ number_format($listing->views_count) }}</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.listings.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase tracking-wider shadow-sm transition cursor-pointer">
                Save Listing Changes
            </button>
        </div>

    </form>

</div>

<script>
function previewGalleryUploads(input) {
    const container = document.getElementById('gallery-preview-container');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        container.classList.remove('hidden');
        container.classList.add('grid', 'grid-cols-2');
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative aspect-4/3 rounded-xl overflow-hidden bg-slate-200 border border-slate-300';
                wrapper.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <span class="absolute bottom-1 right-1 bg-black/70 text-white text-[9px] px-1.5 py-0.5 rounded font-mono">#${index + 1}</span>
                `;
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    } else {
        container.classList.add('hidden');
        container.classList.remove('grid', 'grid-cols-2');
    }
}
</script>
@endsection
