@extends('admin.layouts.admin')

@section('title', 'User Management')
@section('header_title', 'All Registered Users & Admins')

@section('content')
<div class="space-y-6">

    <!-- Actions & Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-80 flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 shadow-xs">
            <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name, email, phone..." class="w-full text-xs bg-transparent focus:outline-none">
        </form>

        <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5 shadow-sm shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            <span>Add New User / Admin</span>
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500">
                Total <span class="text-slate-900 font-black">{{ $users->total() }}</span> registered accounts
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">User</th>
                        <th class="py-3 px-3">Phone</th>
                        <th class="py-3 px-3">City</th>
                        <th class="py-3 px-3">Role</th>
                        <th class="py-3 px-3">Listings Count</th>
                        <th class="py-3 px-3">Joined Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-semibold">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-100">
                                    <div>
                                        <span class="text-slate-900 font-bold block">{{ $user->name }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-3 text-slate-600 font-medium">
                                {{ $user->phone ?? '—' }}
                            </td>

                            <td class="py-3.5 px-3 text-slate-600 font-medium">
                                {{ $user->city ?? '—' }}
                            </td>

                            <td class="py-3.5 px-3">
                                @if($user->is_admin)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-teal-100 text-teal-800 border border-teal-200">
                                        ★ Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                        User
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3 font-bold text-slate-900">
                                {{ $user->listings_count }} ads
                            </td>

                            <td class="py-3.5 px-3 text-slate-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-bold transition-colors {{ $user->is_admin ? 'border-amber-300 text-amber-800 bg-amber-50 hover:bg-amber-100' : 'border-teal-300 text-teal-800 bg-teal-50 hover:bg-teal-100' }}">
                                            {{ $user->is_admin ? 'Demote' : 'Make Admin' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user and all their listings?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50" title="Delete User">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-bold text-teal-700 italic">Current Session</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
