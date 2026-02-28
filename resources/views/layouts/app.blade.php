<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body class="font-sans scroll-smooth antialiased bg-white text-gray-900">

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