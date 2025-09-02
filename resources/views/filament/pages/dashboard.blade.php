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
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 text-white rounded-2xl shadow-lg p-6">
            <h3 class="text-sm font-medium opacity-80">Jumlah Siswa</h3>
            <p class="text-4xl font-bold mt-2">{{ $siswaCount }}</p>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-700 text-white rounded-2xl shadow-lg p-6">
            <h3 class="text-sm font-medium opacity-80">Jumlah Pelatih</h3>
            <p class="text-4xl font-bold mt-2">{{ $coachCount }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-2xl shadow-lg p-6">
            <h3 class="text-sm font-medium opacity-80">Jumlah Unit</h3>
            <p class="text-4xl font-bold mt-2">{{ $unitCount }}</p>
        </div>
    </div>
</x-filament::page>
