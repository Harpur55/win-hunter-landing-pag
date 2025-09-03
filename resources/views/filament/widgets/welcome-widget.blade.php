<x-filament::widget>
    <x-filament::card>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Selamat datang, {{ auth()->user()->name }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Semoga harimu menyenangkan 🚀
        </p>
    </x-filament::card>
</x-filament::widget>