<x-filament-panels::page.simple>
    <div class="flex justify-center items-center min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">

            {{-- Logo --}}
            <div class="text-center mb-6">
                <img src="{{ asset('assets/images/download.JPG') }}"
                     alt="Logo"
                     class="mx-auto h-16 w-16 rounded-full shadow">
                <h2 class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Lupa Password
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Masukkan email dan password baru Anda.
                </p>
            </div>

            {{-- Form --}}
            <form wire:submit="resetPassword" class="space-y-4">
                {{ $this->form }}

                <x-filament::button type="submit" class="w-full">
                    Reset Password
                </x-filament::button>
            </form>

            {{-- Kembali ke Login --}}
            <div class="text-center mt-4">
                <a href="{{ filament()->getPanel('siswa')->getLoginUrl() }}" 
                   class="text-green-600 hover:text-green-800">
                    ← Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
