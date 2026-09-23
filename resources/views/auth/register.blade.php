@extends('layouts.app')

@section('title', 'Create Free Account | Marketplace')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-sm space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#002f34] to-teal-500 text-white flex items-center justify-center font-black text-xl mx-auto shadow-md">
                M
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Join Marketplace</h1>
            <p class="text-xs text-slate-500">Create an account to post free ads and connect with buyers</p>
        </div>

        <!-- Form -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai, Bengaluru, Delhi..." class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password *</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm Password *</label>
                <input type="password" name="password_confirmation" required placeholder="Repeat your password" class="w-full text-sm px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-slate-950 font-black text-sm uppercase tracking-wider rounded-xl transition-all shadow-md">
                    Create Account
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-teal-700 hover:text-teal-800 underline">
                Sign in here
            </a>
        </div>

    </div>
</div>
@endsection
