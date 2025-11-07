<x-filament-panels::page>
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📜 Riwayat Ujian</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Lihat daftar ujian yang pernah kamu ikuti beserta hasilnya. 
                    Tombol sertifikat hanya muncul jika kamu <b>Lulus</b>.
                </p>
            </div>
        </div>

        {{-- Daftar Riwayat --}}
        @if (count($riwayat) > 0)
            {{-- 💻 Tampilan Desktop --}}
            <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mt-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Nama Ujian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Sabuk Saat Itu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Sabuk Berikutnya</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-black dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-black dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($riwayat as $index => $event)
                            @php
                                $pivot = $event->siswa->first()->pivot ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $event->nama_ujian }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $event->lokasi_ujian ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $pivot->current_belt_level ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $pivot->next_belt_level ?? '-' }}</td>
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($pivot && $pivot->keterangan === 'lulus')
                                        <button 
                                            type="button"
                                            disabled
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white 
                                                   text-sm font-semibold rounded-lg shadow opacity-80 cursor-not-allowed">
                                            🎓 Unduh Sertifikat
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 📱 Tampilan Mobile --}}
            <div class="md:hidden grid gap-4 mt-6">
                @foreach ($riwayat as $index => $event)
                    @php
                        $pivot = $event->siswa->first()->pivot ?? null;
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm bg-white dark:bg-gray-900">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $event->nama_ujian }}</h3>
                            @if ($pivot && $pivot->keterangan === 'lulus')
                                
                            @elseif ($pivot && $pivot->keterangan === 'tidak_lulus')
                                <span class="text-red-600 dark:text-red-400 font-semibold text-sm">❌ Tidak Lulus</span>
                            @elseif ($pivot && $pivot->keterangan === 'on_proses')
                                <span class="text-yellow-600 dark:text-yellow-400 font-semibold text-sm">⏳ On Proses</span>
                            @else
                                <span class="text-gray-400 font-semibold text-sm">-</span>
                            @endif
                        </div>

                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}</p>
                            <p><strong>Lokasi:</strong> {{ $event->lokasi_ujian ?? '-' }}</p>
                            <p><strong>Sabuk Saat Itu:</strong> {{ $pivot->current_belt_level ?? '-' }}</p>
                            <p><strong>Sabuk Berikutnya:</strong> {{ $pivot->next_belt_level ?? '-' }}</p>
                        </div>
@if ($pivot && $pivot->keterangan === 'lulus')
    <div class="mt-3 space-y-2">
        <span class="inline-flex justify-center items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
            ✅ Lulus
        </span>
        <button 
            type="button"
            disabled
            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 text-white 
                   text-sm font-semibold rounded-lg shadow opacity-80 cursor-not-allowed">
            🎓 Unduh Sertifikat
        </button>
    </div>
@endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400">
                ⚠️ Belum ada ujian yang kamu ikuti.
            </div>
        @endif
    </div>
</x-filament-panels::page>
