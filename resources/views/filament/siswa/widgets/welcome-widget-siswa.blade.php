<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 
                   bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 
                   p-6 shadow-sm transition-all duration-300 hover:shadow-md">

            {{-- Header Greeting --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-900 dark:text-gray-100">
                        👋 Selamat datang, 
                        <span class="text-green-600 dark:text-indigo-400">{{ $nama }}</span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelas yang diambil: 
                        <span class="font-semibold uppercase text-indigo-500 dark:text-indigo-300">{{ $kelas }}</span>
                    </p>
                </div>

                {{-- Kuota Card --}}
                <div
                    class="border border-gray-300 dark:border-gray-700 bg-green-300 dark:bg-gray-800 
                           rounded-xl p-4 w-32 text-center shadow-sm hover:shadow-lg 
                           transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl mb-1">🥋</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $kuota['reguler'] ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 tracking-wide uppercase">
                        Reguler
                    </div>
                </div>
            </div>

            {{-- Decorative Line / Separator --}}
            {{-- <div class="mt-5 border-t border-dashed border-gray-300 dark:border-gray-700"></div> --}}

            {{-- Additional Info (opsional) --}}
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                Semangat berlatih dan terus tingkatkan kemampuanmu! 💪
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
