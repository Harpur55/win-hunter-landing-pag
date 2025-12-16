<x-filament-panels::page.simple>
    <x-slot name="heading">
        Register Siswa
    </x-slot>

    <form wire:submit.prevent="register" class="space-y-6">

        {{-- 🔥 Inilah cara memanggil form Filament --}}
        {{ $this->form }}

       <x-filament::button type="submit"
    class="w-full bg-green-600 hover:bg-green-700 text-white">
    Register
</x-filament::button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('filament.siswa.auth.login') }}"
           class="text-primary-600 hover:underline text-sm">
            Sudah punya akun? Login
        </a>
    </div>
</x-filament-panels::page.simple>
