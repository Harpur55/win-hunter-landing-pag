<x-filament-panels::page.simple>
    <x-slot name="heading">
        Register Siswa
    </x-slot>

    <form method="POST" action="{{ route('siswa.register.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        </div>

        <x-filament::button type="submit" class="w-full">
            Register
        </x-filament::button>
    </form>
</x-filament-panels::page.simple>