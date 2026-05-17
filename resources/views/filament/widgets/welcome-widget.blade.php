<x-filament::widget>
    <x-filament::card
        class="
            overflow-hidden relative
            rounded-2xl
            border border-blue-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-blue-50
            via-sky-50
            to-indigo-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-slate-900

            shadow-sm
            transition-all duration-300
            hover:shadow-lg
        "
    >

        {{-- Background Accent --}}
        <div class="
            absolute inset-0 opacity-20
            bg-[radial-gradient(circle_at_top_right,_#2563eb,_transparent_40%)]
        "></div>

        {{-- Content --}}
        <div class="relative p-6 flex flex-col gap-4">

            {{-- Header --}}
            <div class="flex items-center gap-4">

                {{-- Icon --}}
                <div class="
                    flex items-center justify-center
                    h-12 w-12 rounded-xl

                    bg-gradient-to-br
                    from-blue-500
                    to-indigo-600

                    text-white
                    text-xl
                    shadow-md
                ">
                    👋
                </div>

                {{-- Text --}}
                <div>

                    <h2 class="
                        text-2xl
                        font-bold
                        tracking-tight

                        text-slate-800
                        dark:text-white
                    ">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h2>

                    <p class="
                        text-sm
                        mt-1
                        leading-relaxed

                        text-slate-600
                        dark:text-slate-400
                    ">
                        Semoga harimu produktif dan menyenangkan hari ini.
                    </p>

                </div>

            </div>

            {{-- Decorative Line --}}
            <div class="
                h-1
                w-28
                rounded-full

                bg-gradient-to-r
                from-blue-500
                via-sky-400
                to-indigo-500
            "></div>

        </div>

    </x-filament::card>
</x-filament::widget>