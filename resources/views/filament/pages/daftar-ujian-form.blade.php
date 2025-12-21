<x-filament::page>
    <div class="max-w-3xl mx-auto space-y-8">

        {{-- HEADER EVENT --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                📝 Pendaftaran Ujian
            </h1>

            <div class="mt-4 p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800">
                <h2 class="text-xl font-semibold text-primary-700 dark:text-primary-300">
                    {{ $event->nama_ujian }}
                </h2>

                <div class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                    📅 <strong>{{ $event->tanggal_ujian }}</strong>  
                    <br>
                    📍 <strong>{{ $event->lokasi_ujian }}</strong>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">

            {{-- GRID INPUT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama Lengkap --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Lengkap
                    </label>
                    <input type="text" wire:model.blur="nama_lengkap"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Masukkan nama lengkap siswa">
                    @error('nama_lengkap')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tempat Lahir --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tempat Lahir
                    </label>
                    <input type="text" wire:model.blur="tempat_lahir"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Contoh: Jakarta">
                    @error('tempat_lahir')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tanggal Lahir
                    </label>
                    <input type="date" wire:model.blur="tanggal_lahir"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500">
                    @error('tanggal_lahir')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No Register --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nomor Register
                    </label>
                    <input type="text" wire:model.blur="no_register"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Kode Register Siswa">
                    @error('no_register')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current Belt --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Sabuk Saat Ini
                    </label>
                    <input type="text" wire:model.blur="current_belt_level"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Contoh: Hijau">
                    @error('current_belt_level')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Next Belt --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Sabuk Berikutnya
                    </label>
                    <select wire:model.blur="next_belt_level"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">-- Pilih Sabuk --</option>
                        <option>Putih</option>
                        <option>Kuning</option>
                        <option>Kuning Strip Hijau</option>
                        <option>Hijau</option>
                        <option>Hijau Strip Biru</option>
                        <option>Biru</option>
                        <option>Biru Strip Merah</option>
                        <option>Merah</option>
                        <option>Merah Strip Hitam 1</option>
                        <option>Merah Strip Hitam 2</option>
                        <option>Hitam</option>
                    </select>
                    @error('next_belt_level')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- FOOTER BUTTON STICKY --}}
            <div class="mt-10">
                <button wire:click="submit"
                    class="w-full md:w-auto px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-sm transition">
                    ✅ Daftar Sekarang
                </button>
            </div>
        </div>
    </div>
</x-filament::page>
