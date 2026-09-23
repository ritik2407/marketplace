<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Marketplace - Buy, Sell Products & Hire Services') | OLX Clone</title>
    <meta name="description" content="@yield('meta_description', 'India\'s premier marketplace to buy, sell second-hand & new products, hire local services, post free classified ads.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    
    <!-- Navbar Component -->
    @include('components.navbar')

    <!-- Toast Notifications -->
    @if(session('success'))
        <div id="toast-success" class="fixed bottom-6 right-6 z-50 flex items-center p-4 max-w-md text-slate-800 bg-white rounded-xl shadow-2xl border-l-4 border-teal-500 transition-all duration-300 transform translate-y-0" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-teal-600 bg-teal-100 rounded-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ms-3 text-sm font-medium pr-4">{{ session('success') }}</div>
            <button type="button" onclick="document.getElementById('toast-success').remove()" class="ms-auto -mx-1.5 -my-1.5 text-slate-400 hover:text-slate-900 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-success');
                if (toast) toast.remove();
            }, 6000);
        </script>
    @endif

    @if(session('error') || $errors->any())
        <div id="toast-error" class="fixed bottom-6 right-6 z-50 flex items-center p-4 max-w-md text-slate-800 bg-white rounded-xl shadow-2xl border-l-4 border-rose-500 transition-all duration-300" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-600 bg-rose-100 rounded-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ms-3 text-sm font-medium pr-4">
                @if(session('error'))
                    {{ session('error') }}
                @else
                    {{ $errors->first() }}
                @endif
            </div>
            <button type="button" onclick="document.getElementById('toast-error').remove()" class="ms-auto -mx-1.5 -my-1.5 text-slate-400 hover:text-slate-900 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
