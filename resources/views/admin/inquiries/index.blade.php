@extends('admin.layouts.admin')

@section('title', 'Buyer Inquiries')
@section('header_title', 'All Platform Buyer Inquiries & Messages')

@section('content')
<div class="space-y-6">

    <!-- Search Bar -->
    <div class="flex items-center justify-between">
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="w-full sm:w-80 flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 shadow-xs">
            <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inquiry name, email, message..." class="w-full text-xs bg-transparent focus:outline-none">
        </form>

        <span class="text-xs font-bold text-slate-500">
            Total {{ $inquiries->total() }} messages
        </span>
    </div>

    <!-- Inquiries List -->
    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Buyer Info</th>
                        <th class="py-3 px-3">Regarding Ad</th>
                        <th class="py-3 px-3">Seller</th>
                        <th class="py-3 px-3">Message Preview</th>
                        <th class="py-3 px-3">Date</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-semibold whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $inquiry->name }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $inquiry->email }}</span>
                                @if($inquiry->phone)
                                    <span class="text-[10px] text-slate-400 block font-mono">{{ $inquiry->phone }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 whitespace-nowrap">
                                @if($inquiry->listing)
                                    <a href="{{ route('listings.show', $inquiry->listing->slug) }}" target="_blank" class="font-bold text-teal-700 hover:underline block max-w-xs truncate">
                                        {{ $inquiry->listing->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">#AD-{{ $inquiry->listing_id }} • {{ $inquiry->listing->formatted_price }}</span>
                                @else
                                    <span class="text-slate-400 italic">Deleted listing</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 text-slate-600 whitespace-nowrap">
                                @if($inquiry->listing && $inquiry->listing->user)
                                    <span class="font-bold text-slate-800 block">{{ $inquiry->listing->user->name }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $inquiry->listing->user->email }}</span>
                                @else
                                    <span>—</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 max-w-md">
                                <p class="text-xs text-slate-700 italic bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    "{{ $inquiry->message }}"
                                </p>
                            </td>

                            <td class="py-3.5 px-3 text-slate-400 whitespace-nowrap">
                                {{ $inquiry->created_at->format('M d, Y H:i') }}
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50" title="Delete Inquiry">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                No buyer inquiries recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-100">
            {{ $inquiries->links() }}
        </div>
    </div>

</div>
@endsection
