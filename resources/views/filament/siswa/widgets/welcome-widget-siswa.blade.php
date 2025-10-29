<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="relative overflow-hidden rounded-2xl 
                   border border-gray-300 dark:border-gray-700 
                   bg-gradient-to-br from-white via-gray-50 to-gray-100 
                   dark:from-gray-900 dark:via-gray-800 dark:to-gray-900
                   shadow-[inset_1px_1px_0px_rgba(255,255,255,0.6),inset_-2px_-2px_6px_rgba(0,0,0,0.05)]
                   dark:shadow-[inset_0_0_0_rgba(255,255,255,0.1),_inset_0_0_10px_rgba(255,255,255,0.05)]
                   p-4 sm:p-6 transition-all duration-300 
                   hover:shadow-lg hover:scale-[1.01] hover:border-gray-400 dark:hover:border-gray-600"
        >

            {{-- 🌟 Header Greeting + Kuota di Kanan --}}
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                
                {{-- 👋 Info Siswa --}}
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-xl sm:text-2xl font-bold flex flex-col sm:flex-row items-center justify-center md:justify-start gap-1 sm:gap-2 text-gray-900 dark:text-gray-100">
                        👋 
                        <span>Selamat datang,</span>
                        <span class="text-green-600 dark:text-indigo-400 drop-shadow-sm">
                            {{ $nama }}
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelas: 
                        <span class="font-semibold uppercase text-indigo-600 dark:text-indigo-300 tracking-wide">
                            {{ $kelas }}
                        </span>
                    </p>
                </div>

                {{-- 🧮 Card Kuota di kanan --}}
                @if (!empty($kuota))
                    <div class="flex flex-wrap justify-center md:justify-end gap-3">
                        @foreach ($kuota as $tipe => $jumlah)
                            <div
                                class="relative border border-gray-200 dark:border-gray-700 
                                       bg-gradient-to-br from-green-200 via-green-300 to-green-400 
                                       dark:from-gray-800 dark:via-gray-850 dark:to-gray-900
                                       rounded-xl p-3 sm:p-4 text-center 
                                       shadow-[3px_3px_8px_rgba(0,0,0,0.1),-3px-3px_8px_rgba(255,255,255,0.5)]
                                       dark:shadow-[inset_0_0_10px_rgba(255,255,255,0.05)]
                                       hover:shadow-[inset_-2px_-2px_10px_rgba(255,255,255,0.7),_2px_2px_10px_rgba(0,0,0,0.15)]
                                       transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.03]"
                            >
                                <div class="text-3xl mb-1">
                                    @switch($tipe)
                                        @case('prestasi') 🏆 @break
                                        @case('khusus') 🎯 @break
                                        @case('reguler') 🥋 @break
                                        @case('poomsae') 🌸 @break
                                        @default 💠
                                    @endswitch
                                </div>
                                <div class="text-2xl font-extrabold text-gray-900 dark:text-white drop-shadow-sm">
                                    {{ $jumlah }}
                                </div>
                                <div class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-300 uppercase tracking-wide">
                                    {{ ucfirst($tipe) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 💬 Pesan motivasi --}}
            <div class="mt-6 text-center md:text-left text-sm text-gray-700 dark:text-gray-400 leading-relaxed italic">
                “Semangat berlatih dan terus tingkatkan kemampuanmu! 💪”
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
