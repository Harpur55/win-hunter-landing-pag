<x-filament::page>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mt-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    🏅 Riwayat Kejuaraan
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Berikut daftar kejuaraan yang pernah kamu ikuti beserta hasilnya.
                </p>
            </div>
        </div>

        {{-- Daftar riwayat --}}
        <div class="flex flex-col gap-4">
            @forelse ($riwayat as $item)
                <div
                    class="w-full rounded-xl border border-gray-200 dark:border-gray-700
                           bg-white dark:bg-gray-800 shadow-sm hover:shadow-md
                           transition duration-200 overflow-hidden">

                    <div class="p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- Info kejuaraan --}}
                        <div class="col-span-1 sm:col-span-2 lg:col-span-1 space-y-1.5">
                            <p class="text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Nama Kejuaraan
                            </p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $item['nama_kejuaraan'] }}
                            </h3>

                            <p
                                class="mt-3 text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Grade
                            </p>
                            <p
                                class="inline-flex items-center px-3 py-1 rounded-full text-s
 font-semibold
                                      bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                                {{ $item['grades'] }}
                            </p>

                            <div class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex items-center">
                                    <span class="mr-2">📅</span>
                                    <span>{{ $item['tanggal'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">📍</span>
                                    <span>{{ $item['lokasi'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kategori, Tingkat & Kelas Berat --}}
                        <div class="col-span-1 sm:col-span-1 lg:col-span-1 space-y-3">

                            {{-- Kategori Pertandingan --}}
                            <div>
                                <p
                                    class="text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                    Kategori Pertandingan
                                </p>
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-s
 font-semibold
                     bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ ucfirst($item['kategori_pertandingan'] ?? '-') }}
                                </span>
                            </div>

                            {{-- Tingkat Kategori --}}
                            <div>
                                <p
                                    class="text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                    Tingkat Kategori
                                </p>
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-s
 font-semibold
                     bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200">
                                    {{ $item['tingkat_kategori'] ?? '-' }}
                                </span>
                            </div>

                            {{-- Kelas Berat --}}
                            <div>
                                <p
                                    class="text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                    Kelas Berat (Under)
                                </p>
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-s
 font-semibold
                     bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                                    {{ $item['kelas_berat'] ?? '-' }}
                                </span>
                            </div>

                        </div>

                        {{-- Medali --}}
                        <div
                            class="col-span-1 sm:col-span-1 lg:col-span-1 flex items-start lg:justify-end order-first sm:order-none
">
                            <div class="text-left lg:text-right w-full">
                                <p
                                    class="text-s
 font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">
                                    Medali
                                </p>

                                @php
                                    $med = strtolower($item['medali'] ?? '');
                                @endphp

                                @if ($med === 'emas' || $med === 'gold' || $med === '🥇')
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                                 bg-yellow-400 text-gray-900 text-s
 font-semibold">
                                        🥇 Emas
                                    </span>
                                @elseif ($med === 'perak' || $med === 'silver' || $med === '🥈')
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                                 bg-gray-300 text-gray-900 text-s
 font-semibold">
                                        🥈 Perak
                                    </span>
                                @elseif ($med === 'perunggu' || $med === 'bronze' || $med === '🥉')
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                                 bg-orange-400 text-white text-s
 font-semibold">
                                        🥉 Perunggu
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                                 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                 text-s
 font-medium">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div
                    class="w-full py-10 text-center text-gray-500 dark:text-gray-400
                           border border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                    ⚠️ Belum ada riwayat kejuaraan tercatat.
                </div>
            @endforelse
        </div>

    </div>
</x-filament::page>
