<x-filament-panels::page>
    <div class="max-w-lg mx-auto mt-10">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-emerald-500">Ubah Password</h2>
            <p class="text-gray-400 text-sm mt-2">
                Silakan isi form berikut untuk memperbarui password Anda.
            </p>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="changePassword"
              class="space-y-6 bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-emerald-500/20">

            {{-- Password Lama --}}
            <div>
                <label for="old_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Password Lama
                </label>
                <input
                    id="old_password"
                    type="password"
                    wire:model.defer="old_password"
                    placeholder="Masukkan password lama"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                />
                @error('old_password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div>
                <label for="new_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Password Baru
                </label>
                <input
                    id="new_password"
                    type="password"
                    wire:model.defer="new_password"
                    placeholder="Masukkan password baru"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                />
                @error('new_password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Konfirmasi Password Baru
                </label>
                <input
                    id="confirm_password"
                    type="password"
                    wire:model.defer="confirm_password"
                    placeholder="Ketik ulang password baru"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                />
                @error('confirm_password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-4">
                <button
                    type="submit"
                    wire:confirm="Apakah Anda yakin ingin mengubah password?"
                    class="w-full py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
