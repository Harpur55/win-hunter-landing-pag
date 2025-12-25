<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left border border-gray-300 rounded-lg shadow">
        <thead class="bg-gradient-to-r from-sky-600 to-indigo-700 text-white">
            <tr>
                <th class="border px-3 py-2 text-center">#</th>
                <th class="border px-3 py-2">Nama Kejuaraan</th>
                <th class="border px-3 py-2">Tanggal</th>
                <th class="border px-3 py-2">Lokasi</th>
                <th class="border px-3 py-2">Kategori</th>
                <th class="border px-3 py-2">Kategori Atlit</th>
                <th class="border px-3 py-2">Under</th>
                <th class="border px-3 py-2 text-center">Medali</th>
                <th class="border px-3 py-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kejuaraans as $index => $k)
                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-indigo-50 transition">
                    <td class="border px-3 py-2 text-center font-semibold text-gray-700">
                        {{ $index + 1 }}
                    </td>
                    <td class="border px-3 py-2 text-sky-700 font-medium">
                        {{ $k->nama_kejuaraan ?? '-' }}
                    </td>
                    <td class="border px-3 py-2 text-gray-600">
                        {{ $k->tanggal_mulai ? \Carbon\Carbon::parse($k->tanggal_mulai)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td class="border px-3 py-2 text-gray-600">
                        {{ $k->lokasi ?? '-' }}
                    </td>
                    <td class="border px-3 py-2 text-gray-700">
                        {{ $k->pivot->kategori_pertandingan ?? '-' }}
                    </td>
                    <td class="border px-3 py-2 text-gray-700">
                        {{ $k->pivot->kategori_atlit ?? '-' }}
                    </td>
                    <td class="border px-3 py-2 text-gray-700">
                        {{ ucfirst($k->pivot->kelas_berat ?? '-') }}
                    </td>

                    {{-- 🏅 Warna Medali --}}
                    <td class="border px-3 py-2 text-center">
                        @php
                            $medali = strtolower($k->pivot->medali ?? '');
                            $warnaMedali = match($medali) {
                                'emas' => 'bg-yellow-400 text-yellow-900',
                                'perak' => 'bg-gray-300 text-gray-800',
                                'perunggu' => 'bg-orange-400 text-white',
                                default => 'bg-gray-200 text-gray-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $warnaMedali }}">
                            {{ $k->pivot->medali ?? '-' }}
                        </span>
                    </td>

                    {{-- 🟢 Status --}}
                    <td class="border px-3 py-2 text-center">
                        @php
                            $status = strtolower($k->pivot->status ?? '');
                            $warnaStatus = match($status) {
                                'aktif' => 'bg-green-100 text-green-700 border border-green-400',
                                'selesai' => 'bg-blue-100 text-blue-700 border border-blue-400',
                                'diskualifikasi' => 'bg-red-100 text-red-700 border border-red-400',
                                default => 'bg-gray-100 text-gray-700 border border-gray-300'
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $warnaStatus }}">
                            {{ ucfirst($k->pivot->status ?? '-') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-gray-500 italic">
                        Belum pernah mengikuti kejuaraan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
