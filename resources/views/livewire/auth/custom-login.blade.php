<x-filament-panels::page.simple>
    <div class="flex justify-center">
        <div
            class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transition-colors duration-300"
        >
            {{-- Logo + Judul --}}
            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/images/download.JPG') }}" 
                    alt="Logo"
                    class="mx-auto h-16 w-16 rounded-full shadow-md">
                <h2 class="mt-6 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Login Dashboard
                </h2>
            </div>

            {{-- Form Login --}}
            <x-filament-panels::form wire:submit="authenticate" class="space-y-4 my-4">
                {{ $this->form }}

                {{-- Tombol login custom full width --}}
                <div class="mt-4">
                    <button type="submit" 
                        class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                        Login
                    </button>
                </div>
            </x-filament-panels::form>

            {{-- Tambahan bagian register --}}
            
        </div>
    </div>
</x-filament-panels::page.simple>
