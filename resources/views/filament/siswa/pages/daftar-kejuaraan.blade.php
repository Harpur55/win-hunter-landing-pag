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
        <div class="flex flex-col gap-4 mt-4">
            @forelse ($events as $event)
                <div
                    class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                           rounded-xl shadow-sm hover:shadow-md transition duration-300 overflow-hidden">

                    <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                {{ $event->nama_kejuaraan }}
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p>📅 {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                                    – {{ \Carbon\Carbon::parse($event->tanggal_selesai)->translatedFormat('d F Y') }}
                                </p>
                                <p>📍 {{ $event->lokasi }}</p>
                            </div>
                        </div>

                        {{-- Tombol Daftar --}}
                        <div class="flex-shrink-0">
                            <button wire:click="openForm({{ $event->id }})"
                                class="w-full sm:w-auto px-6 py-2.5 bg-[#22c55e] hover:bg-[#16a34a] 
                                       text-white font-semibold rounded-lg transition focus:ring-2 
                                       focus:ring-offset-2 focus:ring-[#22c55e]">
                                ✅ Daftar Sekarang
                            </button>
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
                                <input type="text" wire:model="nama_lengkap"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                            </div>

                            {{-- Tempat Lahir --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
                                <input type="text" wire:model="tempat_lahir"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                                <input type="date" wire:model="tanggal_lahir"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                            </div>

                            {{-- Sabuk Saat Ini --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Sabuk Saat Ini</label>
                                <input type="text" wire:model="current_belt_level" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-100 dark:bg-gray-800 px-4 py-2.5 cursor-not-allowed" />
                            </div>

                            {{-- Kategori Atlit --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori Atlit</label>
                                <input type="text" wire:model="kategori_atlit" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                           bg-gray-100 dark:bg-gray-800 px-4 py-2.5 cursor-not-allowed" />
                            </div>
                        </div>

                        {{-- === KOLOM KANAN === --}}
                        <div class="space-y-4">

                            {{-- Kategori Pertandingan --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori Pertandingan</label>
                                <select wire:model="kategori_pertandingan"
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
                            @if ($kategori_pertandingan === 'kyorugi')
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Berat Badan (kg)</label>
                                    <input type="number" wire:model="berat_badan"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tinggi Badan (cm)</label>
                                    <input type="number" wire:model="tinggi_badan"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                            @endif

                            {{-- Jika Poomsae --}}
                            @if ($kategori_pertandingan === 'poomsae')
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tageuk (boleh kosong)</label>
                                    <input type="text" wire:model="tageuk"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]" />
                                </div>
                                <div class="transition-all duration-300">
                                    <label class="block text-sm font-medium mb-1">Tingkat Kategori</label>
                                    <select wire:model="tingkat_kategori"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                                               bg-gray-50 dark:bg-gray-800 px-4 py-2.5 focus:ring-2 focus:ring-[#22c55e]">
                                        <option value="">-- Pilih Tingkat --</option>
                                        @foreach ($tingkatKategoriOptions as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div
                        class="flex flex-col sm:flex-row justify-end gap-3 px-6 py-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
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
