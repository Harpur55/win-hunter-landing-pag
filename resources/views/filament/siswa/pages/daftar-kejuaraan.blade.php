<x-filament::page>
    {{-- 🏆 DAFTAR KEJUARAAN --}}
    <div class="space-y-6">
        @foreach ($this->kejuaraans as $kejuaraan)
            <div
                class="bg-gray-800/80 border border-gray-600/60 hover:border-indigo-400/50 
                       p-6 rounded-xl shadow-md hover:shadow-indigo-500/20 flex justify-between items-center 
                       transition-all duration-200 backdrop-blur-sm"
            >
                <div>
                    <h3 class="font-bold text-lg text-gray-100 tracking-wide">
                        {{ strtoupper($kejuaraan->nama_kejuaraan) }}
                    </h3>
                    <p class="text-sm text-gray-400 flex items-center gap-2">
                        📍 {{ $kejuaraan->lokasi }}
                        <span>|</span>
                        📅 {{ \Carbon\Carbon::parse($kejuaraan->tanggal_mulai)->format('d M Y') }}
                    </p>
                </div>

                <button
                    wire:click="bukaFormDaftar({{ $kejuaraan->id }})"
                    class="px-5 py-2 rounded-lg font-semibold text-white bg-gradient-to-r 
                           from-green-500 to-emerald-600 hover:from-emerald-500 hover:to-green-500 
                           shadow-md hover:shadow-green-500/40 transition-all duration-200"
                >
                    Daftar
                </button>
            </div>
        @endforeach
    </div>

    {{-- 🧾 MODAL PENDAFTARAN --}}
    <x-filament::modal id="form-daftar" :visible="$isOpen" width="5xl" :slide-over="false">
        <x-slot name="heading">
            <div class="flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-gray-100">
                📝 Form Pendaftaran Kejuaraan
            </div>
        </x-slot>

        <div class="px-4 py-2 space-y-6">
            {{-- Informasi Pembuka --}}
            <div class="bg-gray-900/30 border border-gray-700/40 rounded-xl p-4">
                <p class="text-sm text-gray-400 leading-relaxed">
                    Lengkapi data diri dan kategori pertandingan dengan benar sebelum mengirim pendaftaran.
                </p>
            </div>

            {{-- FORM PENDAFTARAN TANPA KOLOM --}}
            <div class="flex flex-col gap-5 text-gray-200">
                {{-- Kategori Pertandingan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Kategori Pertandingan</label>
                    <select wire:model.live="kategori_pertandingan"
                        class="w-full rounded-lg bg-gray-800 border-gray-700 focus:border-indigo-500 text-gray-100">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kyorugi">Kyorugi</option>
                        <option value="poomsae">Poomsae</option>
                    </select>
                </div>

                {{-- Jika kategori Kyorugi --}}
                @if ($kategori_pertandingan === 'kyorugi')
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Berat Badan (kg)</label>
                        <input type="number" wire:model="berat_badan"
                            class="w-full rounded-lg bg-gray-800 border-gray-700 focus:border-indigo-500 text-gray-100" />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" wire:model="tinggi_badan"
                            class="w-full rounded-lg bg-gray-800 border-gray-700 focus:border-indigo-500 text-gray-100" />
                    </div>
                @endif

                {{-- Jika kategori Poomsae --}}
                @if ($kategori_pertandingan === 'poomsae')
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Taegeuk</label>
                        <select wire:model="tageuk"
                            class="w-full rounded-lg bg-gray-800 border-gray-700 focus:border-indigo-500 text-gray-100">
                            <option value="">-- Pilih Taegeuk --</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Taegeuk {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Kategori (Beginer / Advance)</label>
                        <select wire:model="tingkat_kategori"
                            class="w-full rounded-lg bg-gray-800 border-gray-700 focus:border-indigo-500 text-gray-100">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="Beginer">Beginer</option>
                            <option value="Advance">Advance</option>
                        </select>
                    </div>
                @endif

                {{-- Kelompok Usia Otomatis --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Kelompok Usia</label>
                    <input type="text" wire:model="kategori_atlit" readonly
                        class="w-full rounded-lg bg-gray-700 border-gray-600 text-gray-200" />
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-700/40">
                <x-filament::button color="gray" wire:click="tutupForm" icon="heroicon-o-x-mark">
                    Batal
                </x-filament::button>

                <x-filament::button color="success" wire:click="daftar" icon="heroicon-o-paper-airplane">
                    Kirim Pendaftaran
                </x-filament::button>
            </div>
        </div>
    </x-filament::modal>

    {{-- 📢 Trigger Modal --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModalDaftar', () => {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'form-daftar' } }))
            })
        })
    </script>
</x-filament::page>
