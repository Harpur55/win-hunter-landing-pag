<x-filament-panels::page>
    <div
        class="min-h-screen bg-gradient-to-br from-sky-100 via-white to-indigo-100 
                dark:from-gray-900 dark:via-gray-950 dark:to-gray-900 py-10 px-4">

        <div class="max-w-5xl mx-auto space-y-10">
            @php
                $user  = Auth::user();
                $siswa = $this->siswaModel ?? $user->siswa ?? null;

                $fotoProfil = $siswa?->image;
                $fotoProfil = $fotoProfil ? ltrim(str_replace('public/', '', $fotoProfil), '/') : null;

                $fotoProfilUrl = $fotoProfil && file_exists(public_path('storage/' . $fotoProfil))
                    ? asset('storage/' . $fotoProfil)
                    : asset('assets/images/default_image_profile.jpg');
            @endphp

            {{-- 🔹 Header Profil --}}
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-6
                       bg-gradient-to-r from-sky-400 via-sky-500 to-indigo-600
                       text-white rounded-3xl p-6 shadow-xl
                       ring-1 ring-inset ring-sky-300/50 dark:ring-white/10
                       backdrop-blur-md transition-all duration-300 hover:shadow-2xl">

                {{-- 👤 Foto & Info User --}}
                <div class="flex items-center gap-4">
                    <img
                        src="{{ $fotoProfilUrl }}"
                        class="h-20 w-20 rounded-full object-cover ring-4 ring-white/50 dark:ring-white/30 shadow-lg"
                        alt="Foto Profil"
                        onerror="this.src='{{ asset('assets/images/default_image_profile.jpg') }}'"
                    />

                    <div>
                        <h2 class="text-2xl font-extrabold leading-tight text-white">
                            {{ $siswa?->nama_lengkap ?? 'Nama Lengkap' }}
                        </h2>
                        <p class="text-sm text-sky-100">
                            NIS: {{ $siswa?->nis ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- 🔘 Tombol Aksi --}}
                <div class="flex flex-wrap gap-3">
                    @if (!$isEditing)
                        <x-filament::button
                            color="info"
                            icon="heroicon-o-pencil-square"
                            wire:click="edit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            Edit Profil
                        </x-filament::button>
                    @else
                        <x-filament::button
                            color="success"
                            icon="heroicon-o-check"
                            wire:click="save"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            Simpan
                        </x-filament::button>

                        <x-filament::button
                            color="danger"
                            icon="heroicon-o-x-mark"
                            wire:click="$set('isEditing', false)"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            Batal
                        </x-filament::button>
                    @endif
                </div>
            </div>

            {{-- 🔸 Container Form Profil --}}
            <div
                class="rounded-3xl p-8 bg-gradient-to-tr from-white via-sky-50 to-indigo-50
                       dark:from-gray-900 dark:via-gray-800 dark:to-gray-900
                       shadow-2xl ring-2 ring-inset ring-sky-100 dark:ring-primary-900/40
                       backdrop-blur-md border border-gray-200/70 dark:border-gray-700/50
                       hover:ring-sky-300 dark:hover:ring-primary-700 transition-all duration-300">
                {{ $this->form }}
            </div>

            {{-- 🔹 Footer Info --}}
            <div
                class="text-center text-sm text-gray-600 dark:text-gray-400
                       border-t border-gray-200 dark:border-gray-700 pt-4">
                Terakhir diperbarui:
                <span class="font-medium text-gray-900 dark:text-gray-200">
                    {{ now()->translatedFormat('d F Y, H:i') }}
                </span>
            </div>
        </div>
    </div>
</x-filament-panels::page>
