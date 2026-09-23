@extends('admin.layouts.admin')

@section('title', 'Create User / Admin')
@section('header_title', 'Create User Account')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1">
        ← Back to User List
    </a>

    <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xs space-y-6">
        <h3 class="text-base font-black text-slate-900 tracking-tight pb-3 border-b border-slate-100">
            Account Information
        </h3>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Password *</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer bg-teal-50/70 p-3.5 rounded-xl border border-teal-200">
                    <input type="checkbox" name="is_admin" value="1" class="rounded text-teal-600 focus:ring-teal-500">
                    <div>
                        <span class="font-bold text-teal-950 block">Grant Administrator Privileges</span>
                        <span class="text-[10px] text-teal-700 block">User will have full access to this admin control center</span>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase tracking-wider">
                    Create User
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
