<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Pendaftaran Kejuaraan') }}</title>

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl font-bold text-blue-600">
                    {{ config('app.name', 'Kejuaraan') }}
                </span>
            </div>

            <div class="text-sm text-gray-500 hidden md:block">
                Sistem Pendaftaran Kejuaraan
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-1 py-8 px-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name', 'Kejuaraan') }}.
            All rights reserved.
        </div>
    </footer>

</body>
</html>
