<x-filament::page>
    <div class="space-y-10">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 px-3 sm:px-0">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    📋 Daftar Ujian Tersedia
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 max-w-md">
                    Pilih ujian yang ingin kamu ikuti dan pastikan data kamu sudah benar sebelum mendaftar.
                </p>
            </div>
        </div>

        {{-- LIST UJIAN --}}
        <div class="grid grid-cols-1 gap-6 mt-4 sm:mt-6 px-3 sm:px-0">
            @forelse ($events as $event)
                @php
                    $siswa = Auth::user()->siswa;
                    $pivot = $event->siswa()->where('siswa_id', $siswa->id)->first()?->pivot;
                    $isRegistered = !is_null($pivot);
                    $isLulus = $pivot && $pivot->keterangan === 'lulus';
                @endphp

                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition duration-300">
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        {{-- INFO EVENT --}}
                        <div class="w-full sm:w-auto">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                                {{ $event->nama_ujian }}
                            </h3>
                            <div class="mt-1 sm:mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p>📅 {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}</p>
                                <p>📍 {{ $event->lokasi_ujian ?? '-' }}</p>

                                {{-- STATUS PENDAFTARAN --}}
                                @if ($event->is_registration_closed)
                                    <span class="inline-flex items-center mt-1 px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-full">
                                        🔴 Pendaftaran Ditutup
                                    </span>
                                @else
                                    <span class="inline-flex items-center mt-1 px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        🟢 Pendaftaran Dibuka
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            @if ($isRegistered)
                                @if ($isLulus)
                                    <span class="inline-flex justify-center px-4 py-2 text-sm font-medium text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-100 rounded-full text-center">
                                        🎉 Kamu sudah lulus
                                    </span>
                                @else
                                    <span class="inline-flex justify-center px-4 py-2 text-sm font-medium text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-100 rounded-full text-center">
                                        ✅ Sudah Terdaftar
                                    </span>
                                    <button wire:click="batalDaftar({{ $event->id }})"
                                        class="px-5 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg focus:ring-2 focus:ring-red-500 transition w-full sm:w-auto">
                                        ❌ Batal Daftar
                                    </button>
                                @endif
                            @else
                                @if ($event->is_registration_closed)
                                    <button disabled
                                        class="px-5 py-2 text-sm font-semibold text-gray-400 bg-gray-200 dark:bg-gray-700 dark:text-gray-500 rounded-lg cursor-not-allowed w-full sm:w-auto">
                                        ⛔ Pendaftaran Ditutup
                                    </button>
                                @else
                                    <button wire:click="confirmDaftar({{ $event->id }})"
                                        class="px-5 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg focus:ring-2 focus:ring-green-500 transition w-full sm:w-auto">
                                        ✅ Daftar Sekarang
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full py-12 text-center text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                    ⚠️ Tidak ada ujian yang tersedia saat ini.
                </div>
            @endforelse
        </div>

        {{-- MODAL VERIFIKASI --}}
        @if ($showVerification)
            <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center px-3 sm:px-0">
                {{-- WRAPPER MODAL --}}
                <div class="bg-white dark:bg-gray-900 w-full max-w-3xl rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                    {{-- HEADER --}}
                    <div class="flex justify-between items-center px-5 sm:px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
                        <h3 class="text-lg sm:text-xl font-semibold flex items-center gap-2">✏️ Verifikasi & Edit Data Siswa</h3>
                        <button wire:click="batal" class="text-white/80 hover:text-white transition text-lg">✖</button>
                    </div>

                    {{-- FORM --}}
                    <div class="flex-1 overflow-y-auto px-5 sm:px-10 py-6 bg-gray-50 dark:bg-gray-900 text-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Nama Siswa --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                <input type="text" wire:model="nama_lengkap" readonly
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 cursor-not-allowed" />
                            </div>

                            {{-- No Register --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No Register</label>
                                <input type="text" wire:model="no_register"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" />
                            </div>

                            {{-- Tempat Lahir --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                <input type="text" wire:model="tempat_lahir"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" />
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                <input type="date" wire:model="tanggal_lahir"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" />
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Latihan</label>
                                <input type="text" wire:model="unit_nama" readonly
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 cursor-not-allowed" />
                            </div>

                            {{-- Kelas --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kelas</label>
                                <input type="text" wire:model="kelas_nama" readonly
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 cursor-not-allowed" />
                            </div>

                            {{-- Sabuk Saat Ini --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sabuk Saat Ini</label>
                                <input type="text" wire:model="current_belt_level"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" />
                            </div>

                            {{-- Sabuk Berikutnya --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sabuk Berikutnya</label>
                                <select wire:model="next_belt_level"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                                    <option value="">-- Pilih Sabuk Berikutnya --</option>
                                    @foreach ($this->sabukOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 px-5 sm:px-8 py-5 bg-gray-100 dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700">
                        <button wire:click="batal"
                            class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition w-full sm:w-auto">
                            ❌ Batal
                        </button>
                        <button wire:click="daftarUjian"
                            class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 transition w-full sm:w-auto">
                            ✅ Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
