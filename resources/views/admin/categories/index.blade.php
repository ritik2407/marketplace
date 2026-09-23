@extends('admin.layouts.admin')

@section('title', 'Manage Categories & Subcategories')
@section('header_title', 'Marketplace Category Taxonomy')

@section('content')
<div class="space-y-8">

    <!-- Add New Category Form -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-base font-black text-slate-900 tracking-tight">Add New Main Category</h3>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs items-end">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. Commercial Machinery" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-teal-500 font-medium">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Icon (Lucide / name)</label>
                <input type="text" name="icon" placeholder="e.g. truck, laptop, home" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-teal-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                <input type="text" name="description" placeholder="Brief category summary..." class="w-full text-xs px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-teal-500">
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm">
                    + Add Category
                </button>
            </div>
        </form>
    </div>

    <!-- Category Hierarchy Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4 flex flex-col justify-between">
                
                <!-- Category Header -->
                <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm">
                            {{ substr($category->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-extrabold text-sm text-slate-900">{{ $category->name }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-bold">
                                    {{ $category->listings_count }} ads
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400">Slug: /category/{{ $category->slug }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete category {{ $category->name }} and all subcategories?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg" title="Delete Category">
                            🗑️
                        </button>
                    </form>
                </div>

                <!-- Subcategories List -->
                <div class="space-y-2 text-xs flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Subcategories ({{ $category->subcategories->count() }})</span>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($category->subcategories as $sub)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-medium">
                                <span>{{ $sub->name }}</span>
                                <span class="text-[9px] text-slate-400 font-bold">({{ $sub->listings_count }})</span>
                                <form action="{{ route('admin.subcategories.destroy', $sub->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete subcategory?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 font-bold ml-1">×</button>
                                </form>
                            </span>
                        @empty
                            <span class="text-slate-400 text-[11px] italic">No subcategories added yet.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Add Subcategory Quick Input -->
                <form action="{{ route('admin.subcategories.store', $category->id) }}" method="POST" class="pt-3 border-t border-slate-100 flex gap-2">
                    @csrf
                    <input type="text" name="name" required placeholder="+ Add new subcategory..." class="flex-1 text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <button type="submit" class="px-3 py-1.5 bg-[#002f34] hover:bg-[#00474e] text-white text-xs font-bold rounded-lg transition-colors">
                        Add
                    </button>
                </form>

            </div>
        @endforeach
    </div>

</div>
@endsection
