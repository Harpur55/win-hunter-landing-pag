<x-filament::page>
    <div class="text-center my-8">
        <h1 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">
            Selamat Datang di Dashboard 🎉
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">
            Ringkasan data sistem anda
        </p>
    </div>

    <!-- Statistik Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Card Siswa -->
        <div class="rounded-2xl shadow-lg p-6 bg-white dark:bg-gray-800 border-l-4 border-indigo-500">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Jumlah Siswa</h3>
            <p class="text-4xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ $siswaCount }}
            </p>
        </div>

        <!-- Card Pelatih -->
        <div class="rounded-2xl shadow-lg p-6 bg-white dark:bg-gray-800 border-l-4 border-pink-500">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Jumlah Pelatih</h3>
            <p class="text-4xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ $coachCount }}
            </p>
        </div>

        <!-- Card Unit -->
        <div class="rounded-2xl shadow-lg p-6 bg-white dark:bg-gray-800 border-l-4 border-green-500">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Jumlah Unit</h3>
            <p class="text-4xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ $unitCount }}
            </p>
        </div>
    </div>
</x-filament::page>
