<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 
                   bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 
                   p-4 sm:p-6 shadow-sm transition-all duration-300 hover:shadow-md">

            {{-- Header Greeting --}}
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">

                {{-- Info Siswa --}}
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-xl sm:text-2xl font-bold flex flex-col sm:flex-row items-center justify-center md:justify-start gap-1 sm:gap-2 text-gray-900 dark:text-gray-100">
                        👋 <span>Selamat datang,</span>
                        <span class="text-green-600 dark:text-indigo-400">{{ $nama }}</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelas: 
                        <span class="font-semibold uppercase text-indigo-500 dark:text-indigo-300">
                            {{ $kelas }}
                        </span>
                    </p>
                </div>

                {{-- Kuota Card --}}
                <div
                    class="border border-gray-300 dark:border-gray-700 bg-green-300 dark:bg-gray-800 
                           rounded-xl p-3 sm:p-4 w-full sm:w-32 text-center shadow-sm hover:shadow-lg 
                           transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl mb-1">🥋</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $kuota['reguler'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm text-gray-700 dark:text-gray-400 tracking-wide uppercase">
                        Reguler
                    </div>
                </div>

            </div>

            {{-- Pesan motivasi --}}
            <div class="mt-4 text-center md:text-left text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                Semangat berlatih dan terus tingkatkan kemampuanmu! 💪
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
