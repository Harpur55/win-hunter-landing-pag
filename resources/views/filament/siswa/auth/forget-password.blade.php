<x-filament::page>
    <div class="max-w-md mx-auto text-center space-y-4">
        <h2 class="text-2xl font-bold text-emerald-600">Lupa Password</h2>
        <form method="POST" action="{{ route('siswa.password.email') }}" class="space-y-4">
            @csrf
            <x-filament::input type="email" name="email" placeholder="Masukkan email Anda" required />
            <x-filament-panels::button type="submit" color="primary" class="w-full">
                Kirim Link Reset
            </x-filament-panels::button>
        </form>
    </div>
</x-filament::page>
