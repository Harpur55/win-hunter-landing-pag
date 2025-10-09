<x-filament::page>
    <div class="w-full space-y-6 px-6 sm:px-10"> {{-- ✅ Tambah padding kiri & kanan --}}
        
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                🏆 Daftar Kejuaraan
            </h2>
        </div>

        {{-- Daftar Kejuaraan --}}
        @if ($kejuaraans->isEmpty())
            <div class="text-gray-500 text-center py-10">
                Belum ada event kejuaraan yang tersedia.
            </div>
        @else
            <div class="space-y-4 w-full">
                @foreach ($kejuaraans as $kejuaraan)
                    <div
                        class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 ml-5">
                                {{ $kejuaraan->nama_kejuaraan }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($kejuaraan->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($kejuaraan->tanggal_selesai)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 flex items-center gap-1">
                                📍 {{ $kejuaraan->lokasi }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $kejuaraan->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="mt-3 sm:mt-0 flex-shrink-0">
                            <x-filament::button color="success">
                                Daftar
                            </x-filament::button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament::page>
