@extends('admin.layouts.admin')

@section('title', 'Manage Locations')
@section('header_title', 'Marketplace Location Hierarchy (States, Cities & Areas)')

@section('content')
<div class="space-y-8">

    <!-- Add City Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-base font-black text-slate-900 tracking-tight">Add New City</h3>
        
        <form action="{{ route('admin.locations.cities.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs items-end">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">State *</label>
                <select name="state_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-teal-500 font-medium">
                    <option value="">-- Select State --</option>
                    @foreach($states as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">City Name *</label>
                <input type="text" name="name" required placeholder="e.g. Chandigarh, Surat" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-teal-500 font-medium">
            </div>

            <div class="pb-2">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                    <input type="checkbox" name="is_popular" value="1" class="rounded text-teal-600">
                    <span>Show in Homepage Popular Cities</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm">
                    + Add City
                </button>
            </div>
        </form>
    </div>

    <!-- State & City List with Areas -->
    <div class="space-y-6">
        @foreach($states as $state)
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                        <h4 class="font-extrabold text-base text-slate-900">{{ $state->name }}</h4>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $state->cities->count() }} cities</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($state->cities as $city)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-sm text-slate-900">{{ $city->name }}</span>
                                        @if($city->is_popular)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-amber-400 text-slate-900">★ Popular</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <form action="{{ route('admin.locations.cities.popular', $city->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1 text-xs rounded hover:bg-slate-200" title="Toggle Popular City">
                                                {{ $city->is_popular ? '⭐' : '☆' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.locations.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Delete {{ $city->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 font-bold" title="Delete City">✕</button>
                                        </form>
                                    </div>
                                </div>

                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $city->listings_count }} ads active</span>
                            </div>

                            <!-- Areas under this city -->
                            <div class="space-y-1 pt-2 border-t border-slate-200 text-xs">
                                <span class="text-[10px] font-bold uppercase text-slate-400 block">Areas / Localities ({{ $city->areas->count() }})</span>
                                <div class="flex flex-wrap gap-1 max-h-24 overflow-y-auto pr-1">
                                    @foreach($city->areas as $area)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] bg-white border border-slate-200 text-slate-700">
                                            {{ $area->name }}
                                            <form action="{{ route('admin.locations.areas.destroy', $area->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete area?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 ml-1">×</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Add Area Input -->
                            <form action="{{ route('admin.locations.areas.store') }}" method="POST" class="pt-2 border-t border-slate-200 flex gap-1.5">
                                @csrf
                                <input type="hidden" name="city_id" value="{{ $city->id }}">
                                <input type="text" name="name" required placeholder="+ Add area..." class="flex-1 text-[11px] px-2.5 py-1 bg-white border border-slate-200 rounded-lg focus:outline-none">
                                <button type="submit" class="px-2.5 py-1 bg-teal-700 text-white text-[11px] font-bold rounded-lg hover:bg-teal-800">Add</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No cities added in {{ $state->name }} yet.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
