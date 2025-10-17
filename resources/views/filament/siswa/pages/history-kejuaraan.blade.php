<x-filament::page>
    <div class="space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                🏅 Riwayat Kejuaraan
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Berikut daftar kejuaraan yang pernah kamu ikuti beserta hasilnya.
            </p>
        </div>

        {{-- DAFTAR RIWAYAT --}}
        <div class="flex flex-col gap-4 mt-4">
            @forelse ($riwayat as $item)
                <div
                    class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                           rounded-xl shadow-sm hover:shadow-md transition duration-300 overflow-hidden">

                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                        {{-- Kartu utama (nama kejuaraan + tanggal & lokasi) --}}
                        <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nama Kejuaraan</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $item['nama_kejuaraan'] }}
                            </h3>

                            <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <div><span class="font-medium text-gray-700 dark:text-gray-200">📅</span>
                                    <span class="ml-2">{{ $item['tanggal'] }}</span>
                                </div>
                                <div><span class="font-medium text-gray-700 dark:text-gray-200">📍</span>
                                    <span class="ml-2">{{ $item['lokasi'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Nama peserta & kategori pertandingan --}}
                        <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nama Peserta</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $item['nama_peserta'] }}
                            </p>

                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 mb-1">Kategori Pertandingan</p>
                            <p class="inline-block px-3 py-1 rounded-lg text-sm font-semibold
                                      bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                {{ ucfirst($item['kategori_pertandingan'] ?? '-') }}
                            </p>
                        </div>

                        {{-- Medali --}}
                        <div class="col-span-1 sm:col-span-1 lg:col-span-1 flex items-start justify-end">
                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Medali</p>

                                @php
                                    $med = strtolower($item['medali'] ?? '');
                                @endphp

                                @if ($med === 'emas' || $med === 'gold' || $med === '🥇')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-yellow-400 text-gray-900 font-semibold">
                                        🥇 Emas
                                    </span>
                                @elseif ($med === 'perak' || $med === 'silver' || $med === '🥈')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-gray-300 text-gray-900 font-semibold">
                                        🥈 Perak
                                    </span>
                                @elseif ($med === 'perunggu' || $med === 'bronze' || $med === '🥉')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-400 text-white font-semibold">
                                        🥉 Perunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="w-full py-12 text-center text-gray-500 dark:text-gray-400 
                           border border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                    ⚠️ Belum ada riwayat kejuaraan tercatat.
                </div>
            @endforelse
        </div>
    </div>
</x-filament::page>
