<x-filament-panels::page>
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📜 Riwayat Ujian
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Lihat daftar ujian yang pernah kamu ikuti beserta hasilnya.
                    Tombol sertifikat hanya muncul jika kamu <b>Lulus</b> dan sertifikat sudah diterbitkan.
                </p>
            </div>
        </div>

        {{-- Daftar Riwayat --}}
        @if (count($riwayat) > 0)

            {{-- ================= DESKTOP ================= --}}
            <div class="mt-6 hidden md:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Ujian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Sabuk Saat Itu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Sabuk Berikutnya</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                        @foreach ($riwayat as $index => $event)
                            @php
                                $pivot = $event->siswa
                                    ->firstWhere('id', auth()->user()->siswa->id)
                                    ?->pivot;

                                $sertifikat = $event->ujianSiswa->first()?->sertifikat;
                            @endphp

                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $event->nama_ujian }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $event->lokasi_ujian ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $pivot->current_belt_level ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $pivot->next_belt_level ?? '-' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-sm">
                                    @if ($pivot?->keterangan === 'lulus')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            Lulus
                                        </span>
                                    @elseif ($pivot?->keterangan === 'on_proses')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                            On Progress
                                        </span>
                                    @elseif ($pivot?->keterangan === 'tidak_lulus')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                            Tidak Lulus
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center text-sm">
                                    @if ($sertifikat && $sertifikat->is_active)
                                        <a
                                            href="{{ Storage::url($sertifikat->file_pdf) }}"
                                            target="_blank"
                                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                        >
                                            🎓 Unduh Sertifikat
                                        </a>
                                       
                                    @elseif ($pivot?->keterangan === 'lulus')
                                        <span class="text-xs text-gray-500">
                                            ⏳ Sertifikat sedang diproses
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ================= MOBILE ================= --}}
            <div class="mt-6 grid gap-4 md:hidden">
                @foreach ($riwayat as $event)
                    @php
                        $pivot = $event->siswa
                            ->firstWhere('id', auth()->user()->siswa->id)
                            ?->pivot;

                        $sertifikat = $event->ujianSiswa->first()?->sertifikat;
                    @endphp

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <div class="mb-2 flex items-start justify-between gap-2">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $event->nama_ujian }}
                            </h3>

                            @if ($pivot?->keterangan === 'lulus')
                                <span class="text-xs font-semibold text-green-600">
                                    ✅ Lulus
                                </span>
                            @elseif ($pivot?->keterangan === 'on_proses')
                                <span class="text-xs font-semibold text-yellow-600">
                                    ⏳ On Progress
                                </span>
                            @elseif ($pivot?->keterangan === 'tidak_lulus')
                                <span class="text-xs font-semibold text-red-600">
                                    ❌ Tidak Lulus
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </div>

                        <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                            <p>
                                <b>Tanggal:</b>
                                {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}
                            </p>
                            <p>
                                <b>Lokasi:</b>
                                {{ $event->lokasi_ujian ?? '-' }}
                            </p>
                            <p>
                                <b>Sabuk Saat Itu:</b>
                                {{ $pivot->current_belt_level ?? '-' }}
                            </p>
                            <p>
                                <b>Sabuk Berikutnya:</b>
                                {{ $pivot->next_belt_level ?? '-' }}
                            </p>
                        </div>

                        @if ($sertifikat && $sertifikat->is_active)
                            <a
                                href="{{ Storage::url($sertifikat->file_pdf) }}"
                                target="_blank"
                                class="mt-3 block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                            >
                                🎓 Unduh Sertifikat
                            </a>
                           
                        @elseif ($pivot?->keterangan === 'lulus')
                            <div class="mt-3 text-center text-xs text-gray-500">
                                ⏳ Sertifikat sedang diproses
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                ⚠️ Belum ada ujian yang kamu ikuti.
            </div>
        @endif

    </div>
</x-filament-panels::page>
