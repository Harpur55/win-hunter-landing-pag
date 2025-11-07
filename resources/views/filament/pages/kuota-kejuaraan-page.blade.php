<x-filament-panels::page>
    <div 
        class="p-6 space-y-6 transition-opacity duration-300" 
        wire:loading.class="opacity-50" 
        wire:target="activeTab"
    >

        {{-- 🔹 Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Kuota Kejuaraan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola kuota kejuaraan per kelas dan pantau penggunaan kuota oleh siswa.
                </p>
            </div>
        </div>

        {{-- 🔹 Tab Selector --}}
        <div class="flex flex-wrap items-center gap-2 pb-2 relative">
            <button 
                wire:click="$set('activeTab', 'kelas')" 
                wire:key="tab-kelas"
                wire:loading.attr="disabled"
                wire:target="activeTab"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150
                    {{ $activeTab === 'kelas' 
                        ? 'bg-primary-600 text-white shadow-sm' 
                        : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                <x-heroicon-o-academic-cap class="w-4 h-4" />
                Kuota per Kelas
            </button>

            <button 
                wire:click="$set('activeTab', 'siswa')" 
                wire:key="tab-siswa"
                wire:loading.attr="disabled"
                wire:target="activeTab"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150
                    {{ $activeTab === 'siswa' 
                        ? 'bg-primary-600 text-white shadow-sm' 
                        : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                <x-heroicon-o-user-group class="w-4 h-4" />
                Monitoring Siswa
            </button>

            {{-- 🔄 Loader kecil di tab --}}
            <div 
                wire:loading.delay 
                wire:target="activeTab"
                class="absolute right-0 -top-2 flex items-center justify-center"
            >
                <svg class="w-5 h-5 text-primary-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                </svg>
            </div>
        </div>

        {{-- 🔹 Table Section --}}
        <div class="mt-4 relative" wire:key="tab-content-{{ $activeTab }}">
            {{-- Hilangkan border ganda --}}
            <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm">
                {{ $this->table }}
            </div>

            {{-- 🔄 Overlay Loading di Tengah --}}
            <div 
                wire:loading.flex 
                wire:target="activeTab"
                class="absolute inset-0 bg-white/70 dark:bg-gray-900/70 backdrop-blur-sm 
                       flex flex-col items-center justify-center rounded-xl z-10 transition-all duration-300"
            >
                <svg class="w-8 h-8 text-primary-600 animate-spin mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-300 font-medium">Memuat data...</span>
            </div>
        </div>

    </div>
</x-filament-panels::page>
