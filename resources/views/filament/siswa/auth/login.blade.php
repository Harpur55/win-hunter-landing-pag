<x-filament-panels::page.simple>
    <div class="flex justify-center min-h-screen items-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

            {{-- Logo --}}
            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/images/download.JPG') }}" 
                     alt="Logo"
                     class="mx-auto h-16 w-16 rounded-full shadow-md">
                <h2 class="mt-6 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Login Siswa
                </h2>
            </div>

            {{-- Form Login --}}
            <x-filament-panels::form wire:submit="authenticate" class="space-y-4 my-4">
                {{ $this->form }}

                <div class="mt-4">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                        Login
                    </button>
                </div>

                {{-- Lupa Password --}}
                <div class="mt-2 text-right text-sm">
                    <a href="/siswa/forget-password" 
                       class="text-primary-600 hover:text-primary-800">
                        Lupa Password?
                    </a>
                </div>
            </x-filament-panels::form>

            {{-- Daftar --}}
            <div class="mt-4 text-center">
                <a href="/siswa/register"
                   class="inline-block w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition text-center">
                    Daftar di sini
                </a>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>
