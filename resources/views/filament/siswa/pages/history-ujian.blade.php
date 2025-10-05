<x-filament-panels::page>
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📜 Riwayat Ujian</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Lihat daftar ujian yang pernah kamu ikuti beserta hasilnya.
                </p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        @if (count($riwayat) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mt-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">NO</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Nama Ujian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Sabuk Saat Itu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Sabuk Berikutnya</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-white dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($riwayat as $index => $event)
                            @php
                                $pivot = $event->siswa->first()->pivot ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-white dark:text-gray-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $event->nama_ujian }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $event->lokasi_ujian ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-white dark:text-gray-300">{{ $pivot->current_belt_level ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-white dark:text-gray-300">{{ $pivot->next_belt_level ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($pivot && $pivot->keterangan === 'lulus')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            ✅ Lulus
                                        </span>
                                    @elseif ($pivot && $pivot->keterangan === 'on_proses')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            ⏳ On Proses
                                        </span>
                                    @elseif ($pivot && $pivot->keterangan === 'tidak_lulus')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            ❌ Tidak Lulus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-white dark:bg-gray-700 dark:text-gray-200">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400">
                ⚠️ Belum ada ujian yang kamu ikuti.
            </div>
        @endif
    </div>
</x-filament-panels::page>
