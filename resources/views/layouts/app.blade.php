<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body class="font-sans scroll-smooth antialiased bg-white text-gray-900">

    <!-- Loading Screen -->
<div id="loading-screen"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black transition-opacity duration-700">

    <div class="relative flex items-center justify-center">

     
        <div class="absolute w-40 h-40 rounded-full 
                    border-4 border-transparent 
                    border-t-emerald-400 border-r-emerald-400 
                    animate-spin">
        </div>

        <!-- Soft Glow -->
        <div class="absolute w-52 h-52 rounded-full 
                    bg-emerald-500/20 blur-2xl animate-pulse">
        </div>

        <!-- Logo Circle -->
        <div class="relative w-28 h-28 rounded-full 
                    bg-white shadow-2xl 
                    flex items-center justify-center overflow-hidden">

            <img src="{{ asset('assets/images/download.jpg') }}"
                 alt="Logo"
                 class="w-20 h-20 object-contain">
        </div>

    </div>
</div>

    {{-- Skip to content (Accessibility) --}}
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-blue-600 text-white px-4 py-2 rounded z-50">
        Skip to content
    </a>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Global Modal (gallery / doc etc) --}}
    @include('partials.modal')

    {{-- Global Scripts --}}
    @include('partials.scripts')

    {{-- Page Specific Scripts --}}
    @stack('scripts')

</body>
</html>