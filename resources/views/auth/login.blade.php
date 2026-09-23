@extends('layouts.app')

@section('title', 'Sign In | Marketplace')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-sm space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#002f34] to-teal-500 text-white flex items-center justify-center font-black text-xl mx-auto shadow-md">
                M
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-xs text-slate-500">Sign in to manage your listings and contact sellers</p>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <input type="password" name="password" required placeholder="••••••••" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-teal-600 focus:ring-teal-500">
                    <span class="text-xs font-semibold text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#002f34] hover:bg-[#00474e] text-white font-bold text-sm rounded-xl uppercase tracking-wider transition-all shadow-md">
                Sign In
            </button>
        </form>

        <!-- Demo Accounts Helper Box -->
        <div class="p-4 rounded-2xl bg-teal-50/70 border border-teal-200/80 text-xs text-slate-700 space-y-2">
            <p class="font-bold text-teal-950 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Demo Accounts (Click to test):
            </p>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <button type="button" onclick="fillDemo('rahul@example.com', 'password123')" class="p-2 bg-white rounded-lg border border-teal-200 hover:bg-teal-100 text-left">
                    <span class="font-bold block text-slate-900">Rahul Sharma</span>
                    <span class="text-slate-500 block truncate">rahul@example.com</span>
                </button>
                <button type="button" onclick="fillDemo('priya@example.com', 'password123')" class="p-2 bg-white rounded-lg border border-teal-200 hover:bg-teal-100 text-left">
                    <span class="font-bold block text-slate-900">Priya Patel</span>
                    <span class="text-slate-500 block truncate">priya@example.com</span>
                </button>
            </div>
            <p class="text-[10px] text-slate-500 text-center">Password for all demo accounts: <code class="font-mono bg-white px-1 py-0.5 rounded">password123</code></p>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="font-bold text-teal-700 hover:text-teal-800 underline">
                Create one free
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function fillDemo(email, pass) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = pass;
    }
</script>
@endpush
@endsection
