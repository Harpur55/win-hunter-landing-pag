<x-filament-panels::page.simple>
    <div class="flex justify-center min-h-screen items-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

            {{-- Logo --}}
            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/images/download.JPG') }}" 
                     alt="Logo"
                     class="mx-auto h-16 w-16 rounded-full shadow-md">
                <h2 class="mt-6 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Lupa Password
                </h2>
            </div>

            @if($message)
                <div class="bg-blue-100 text-blue-700 p-3 rounded mb-4 text-sm">
                    {{ $message }}
                </div>
            @endif

            {{-- Step 1: Input Email --}}
            @if($step === 1)
                <x-filament-panels::form wire:submit.prevent="checkEmail" class="space-y-4 my-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model.defer="email" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                            Lanjut
                        </button>
                    </div>

                    <div class="mt-2 text-right text-sm">
                        <a href="{{ route('filament.siswa.auth.login') }}" 
                           class="text-primary-600 hover:text-primary-800">
                            Kembali ke login
                        </a>
                    </div>
                </x-filament-panels::form>
            @endif

            {{-- Step 2: Reset Password --}}
            @if($step === 2)
                <x-filament-panels::form wire:submit.prevent="resetPassword" class="space-y-4 my-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" wire:model.defer="password" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" wire:model.defer="password_confirmation" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                            Reset Password
                        </button>
                    </div>

                    <div class="mt-2 text-right text-sm">
                        <a href="{{ route('filament.siswa.auth.login') }}" 
                           class="text-primary-600 hover:text-primary-800">
                            Kembali ke login
                        </a>
                    </div>
                </x-filament-panels::form>
            @endif

        </div>
    </div>
</x-filament-panels::page.simple>
