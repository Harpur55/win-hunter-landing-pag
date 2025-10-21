<x-filament::widget>
    <x-filament::card
        class="overflow-hidden relative border border-gray-200/70 dark:border-gray-800/60 
               rounded-2xl backdrop-blur-md
               bg-gradient-to-br from-[#fefcf9] via-[#fdf7f2] to-[#f9ece3]
               dark:from-[#0f0f12] dark:via-[#141418] dark:to-[#1a1a22]
               transition-all duration-700 ease-out
               hover:shadow-[0_6px_16px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]
               hover:border-amber-300/50 dark:hover:border-amber-600/40 hover:-translate-y-1">

        {{-- ✨ Lapisan aura lembut --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] 
                    from-amber-200/20 via-rose-100/15 to-yellow-100/10
                    dark:from-indigo-800/15 dark:via-purple-700/10 dark:to-fuchsia-900/15
                    opacity-70 blur-3xl animate-[aurora_15s_ease-in-out_infinite]"></div>

        {{-- 🌈 Efek kilau lembut --}}
        <div class="absolute inset-0 rounded-2xl ring-1 ring-amber-100/50 dark:ring-purple-700/15"></div>

        {{-- 🪶 Konten utama --}}
        <div class="relative p-6 flex flex-col gap-3 animate-[fadeInUp_0.8s_ease-out]">
            <h2 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent 
                       bg-gradient-to-r from-amber-700 via-pink-600 to-rose-500
                       dark:from-amber-300 dark:via-fuchsia-400 dark:to-pink-400">
                🌟 Selamat Datang, {{ auth()->user()->name }}
            </h2>

            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                Semoga harimu penuh inspirasi dan energi positif ✨
            </p>

            {{-- Garis dekoratif --}}
            <div class="h-[3px] w-24 mt-2 rounded-full 
                        bg-gradient-to-r from-amber-500 via-rose-400 to-pink-500 
                        dark:from-fuchsia-500 dark:via-amber-400 dark:to-pink-400 
                        animate-[expand_1s_ease-out]"></div>
        </div>
    </x-filament::card>

    {{-- 🌬️ Animasi halus --}}
    <style>
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes expand {
            0% { width: 0; opacity: 0; }
            100% { width: 6rem; opacity: 1; }
        }
        @keyframes aurora {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(10px, -10px) rotate(2deg); }
        }
    </style>
</x-filament::widget>
