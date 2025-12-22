<x-filament::page>
    <div class="space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                🏆 Daftar Kejuaraan
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Pilih kejuaraan yang tersedia dan isi data dengan benar sebelum mendaftar.
            </p>
        </div>

        {{-- LIST KEJUARAAN --}}
        <div class="space-y-4 mt-4">
            @forelse ($events as $event)
                <div
                    class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                           rounded-xl shadow-sm hover:shadow-md transition duration-300 overflow-hidden">

                    <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        {{-- Info Kejuaraan --}}
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                {{ strtoupper($event->nama_kejuaraan) }}
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p>
                                    📅 {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse($event->tanggal_selesai)->translatedFormat('d F Y') }}
                                </p>
                                <p>📍 {{ $event->lokasi }}</p>
                            </div>
                        </div>

                        <div class="flex-shrink-0 w-full sm:w-auto">
                            @if (in_array($event->id, $this->sudahTerdaftar ?? []))
                                {{-- 🟢 Jika siswa sudah terdaftar --}}
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button
                                        class="w-full sm:w-auto px-6 py-2.5 bg-gray-400 text-white font-semibold 
                                               rounded-lg cursor-not-allowed select-none">
                                        🟢 Sudah Terdaftar
                                    </button>

                                    {{-- 🔴 Tombol Batalkan (hanya muncul jika belum dapat medali / medali == tidak_ada) --}}
                                    @php
                                        $medali = strtolower($this->getMedaliByEventId($event->id) ?? '');
                                    @endphp

                                    @if ($medali === 'tidak_ada' || $medali === '' || $medali === null)
                                        <button wire:click="batalDaftar({{ $event->id }})"
                                            class="w-full sm:w-auto px-6 py-2.5 bg-red-600 hover:bg-red-700 
                                                   text-white font-semibold rounded-lg transition">
                                            ❌ Batalkan
                                        </button>
                                    @elseif ($medali === 'emas' || $medali === 'perak' || $medali === 'perunggu')
                                        <span
                                            class="px-4 py-2.5 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 
                                                   font-semibold rounded-lg text-center select-none">
                                            🏅 Sudah Dapat Medali
                                        </span>
                                    @endif
                                </div>

                            @elseif ($event->is_registration_closed)
                                {{-- 🔒 Jika pendaftaran ditutup --}}
                                <button
                                    class="w-full sm:w-auto px-6 py-2.5 bg-gray-500 text-white font-semibold 
                                           rounded-lg cursor-not-allowed select-none">
                                    ⛔ Pendaftaran Ditutup oleh Admin
                                </button>

                            @else
                                {{-- ✅ Jika masih terbuka & belum punya medali --}}
                                <button wire:click="openForm({{ $event->id }})"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#22c55e] hover:bg-[#16a34a] 
                                           text-white font-semibold rounded-lg transition focus:ring-2 
                                           focus:ring-offset-2 focus:ring-[#22c55e]">
                                    ✅ Daftar Sekarang
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div
                    class="w-full py-12 text-center text-gray-500 dark:text-gray-400 
                           border border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                    ⚠️ Belum ada kejuaraan tersedia.
                </div>
            @endforelse
        </div>

        {{-- MODAL PENDAFTARAN --}}
        @if ($isOpen)
            <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center px-4">
                <div
                    class="bg-white dark:bg-gray-900 w-full max-w-4xl rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-y-auto max-h-[90vh]">

                    {{-- HEADER --}}
                    <div class="px-6 py-4 bg-[#22c55e] text-white flex justify-between items-center">
                        <h3 class="text-lg font-semibold">✏️ Form Pendaftaran Kejuaraan</h3>
                        <button wire:click="batal" class="text-white/80 hover:text-white">✖</button>
                    </div>

                    {{-- BODY --}}
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 text-gray-800 dark:text-gray-100">

                        {{-- === KOLOM KIRI === --}}
                        <div class="space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                                <input type="text" wire:model="data.nama_lengkap"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                            </div>

                            {{-- Tempat Lahir --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
                                <input type="text" wire:model="data.tempat_lahir"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]"
                                    readonly />
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Tanggal Lahir <span class="text-red-600">*</span>
                                </label>
                                <input type="date" wire:model="data.tanggal_lahir"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]
                                           @error('data.tanggal_lahir') border-red-500 dark:border-red-500 @enderror" />

                                {{-- Pesan error wajib isi --}}
                                @error('data.tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        ⚠️ {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                                <select wire:model="data.jenis_kelamin"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L"
                                        @selected(($data['jenis_kelamin'] ?? null) === 'L' || ($data['jenis_kelamin'] ?? null) === 'Laki-laki')>
                                        Laki-laki
                                    </option>
                                    <option value="P"
                                        @selected(($data['jenis_kelamin'] ?? null) === 'P' || ($data['jenis_kelamin'] ?? null) === 'Perempuan')>
                                        Perempuan
                                    </option>
                                </select>
                            </div>

                            {{-- Sabuk --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Sabuk Saat Ini</label>
                                <input type="text" wire:model="data.sabuk" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-100 dark:bg-gray-800 px-4 py-2.5 cursor-not-allowed" />

                                @if (!empty($data['sabuk']) && strtolower($data['sabuk']) !== 'putih')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium mb-1">Nomor Registrasi</label>
                                        <input type="text" wire:model="data.no_register" maxlength="15"
                                            placeholder="Masukkan nomor registrasi ujian..."
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                                   bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />

                                        @if (empty($data['no_register']))
                                            <div class="mt-2 space-y-1">
                                                <p
                                                    class="text-yellow-600 dark:text-yellow-400 text-sm font-medium flex items-center gap-1">
                                                    ⚠️ Nomor registrasi wajib diisi untuk sabuk di atas putih.
                                                </p>
                                                <div
                                                    class="flex items-start gap-2 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-800 rounded-md p-2.5 text-xs text-gray-700 dark:text-gray-300">
                                                    <span class="text-yellow-600 dark:text-yellow-400 text-lg">📜</span>
                                                    <p>
                                                        Petunjuk: Nomor registrasi dapat ditemukan pada
                                                        <span
                                                            class="font-semibold text-gray-900 dark:text-white">sertifikat
                                                            ujian terakhir</span>
                                                        Anda (biasanya di bagian atas sertifikat).
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Kategori Atlit --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori Atlit</label>
                                <input type="text" wire:model="data.kategori_atlit" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-100 dark:bg-gray-800 px-4 py-2.5 cursor-not-allowed" />
                            </div>
                        </div>

                        {{-- === KOLOM KANAN === --}}
                        <div class="space-y-4">

                            {{-- Kategori Pertandingan --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori Pertandingan</label>
                                <select wire:model.live="data.kategori_pertandingan"
                                    class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                           bg-gray-50 dark:bg-gray-800 px-3 py-1.5 text-sm text-gray-800 dark:text-gray-100
                                           focus:ring-2 focus:ring-[#22c55e] focus:border-[#22c55e]
                                           appearance-none cursor-pointer transition duration-200 ease-in-out 
                                           hover:border-[#22c55e] hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kyorugi">🥋 Kyorugi</option>
                                    <option value="poomsae">💫 Poomsae</option>
                                </select>
                            </div>

                            {{-- Jika Kyorugi --}}
                            @if (($data['kategori_pertandingan'] ?? null) === 'kyorugi')
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Berat Badan (kg)</label>
                                    <input type="number" wire:model="data.berat_badan"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tinggi Badan (cm)</label>
                                    <input type="number" wire:model="data.tinggi_badan"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                            @endif

                            {{-- Jika Poomsae --}}
                            @if (($data['kategori_pertandingan'] ?? null) === 'poomsae')
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tageuk (boleh kosong)</label>
                                    <input type="text" wire:model="data.tageuk"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tingkat Kategori</label>
                                    <select wire:model="data.tingkat_kategori"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]">
                                        <option value="">-- Pilih Tingkat --</option>
                                        <option value="Beginer">Beginer</option>
                                        <option value="Advance">Advance</option>
                                    </select>
                                </div>
                            @endif

                            {{-- Pilihan Pakai Kuota --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium mb-1">Penggunaan Kuota</label>
                                <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" wire:model="pakaiKuota" value="1"
                                            class="text-[#22c55e] border-gray-300 dark:border-gray-700">
                                        <span>Gunakan kuota kelas (kuota akan berkurang)</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" wire:model="pakaiKuota" value="0"
                                            class="text-[#22c55e] border-gray-300 dark:border-gray-700">
                                        <span>Tidak gunakan kuota (kuota kelas tetap utuh)</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 px-6 py-4 ">
                        <button wire:click="batal"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition w-full sm:w-auto">
                            ❌ Batal
                        </button>
                        <button wire:click="daftar"
                            class="px-6 py-2.5 bg-[#22c55e] hover:bg-[#16a34a] text-white rounded-lg font-semibold transition w-full sm:w-auto">
                            ✅ Kirim Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
