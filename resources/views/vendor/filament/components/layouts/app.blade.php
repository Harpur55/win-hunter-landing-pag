@php use Filament\Support\Facades\FilamentView; @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
    <head>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if (filled($favicon = filament()->getFavicon()))
            <link rel="shortcut icon" href="{{ $favicon }}" />
        @endif

        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ filament()->getTitle() }}</title>

        @if ($meta = filament()->getMeta())
            @foreach ($meta as $name => $content)
                <meta name="{{ $name }}" content="{{ $content }}">
            @endforeach
        @endif

        @filamentStyles
        @stack('styles')
        @livewireStyles
    </head>

    <body class="fi-body min-h-full bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-white">
        <div
            x-data="{ sidebarOpen: true }"
            x-bind:class="{ 'overflow-hidden': sidebarOpen }"
            class="flex min-h-screen"
        >
            <!-- Toggle Button -->
            <button
