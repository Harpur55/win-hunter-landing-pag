<x-filament-panels::page.simple>
    <div class="flex justify-center">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/images/download.JPG') }}" 
                    alt="Logo"
                    class="mx-auto h-16 w-16 rounded-full shadow-md">
                <h2 class="mt-6 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Login Siswa
                </h2>
            </div>

            <x-filament-panels::form wire:submit="authenticate" class="space-y-4 my-4">
                {{ $this->form }}

                {{-- CAPTCHA
                 <div class="mt-4">
                 {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                     <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    </div> --}}

                <div class="mt-4">
                    <button type="submit" 
                        class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                        Login
                    </button>
                </div>
            </x-filament-panels::form>

           <div class="mt-4 text-center">
    <a href="{{ route('filament.siswa.auth.register') }}"
       class="w-full inline-block px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition text-center">
        Daftar di sini
    </a>
</div>
    </div>
</x-filament-panels::page.simple>
