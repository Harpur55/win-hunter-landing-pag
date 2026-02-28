  <section id="unit" class="bg-white py-8 px-2">
        <h1 class="text-4xl font-bold mb-6 text-center text-black">Unit</h1>

        <div class="relative overflow-hidden">
            <div id="unit-slider-wrapper"
                class="overflow-x-auto scroll-smooth scrollbar-hide
             snap-x snap-mandatory
             cursor-grab active:cursor-grabbing select-none">

                <div id="unit-slider" class="flex gap-4 w-max px-2">
                    @foreach ($units as $unit)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center
                   bg-white border border-blue-800 rounded-xl shadow-md
                   hover:shadow-xl transition
                   w-[280px] sm:w-[320px] md:w-[360px] lg:w-[400px]
                   min-w-[280px] sm:min-w-[320px] md:min-w-[360px] lg:min-w-[400px]
                   p-4 items-center text-center sm:text-left
                   snap-start">

                            <div class="w-20 h-20 rounded-md border mb-3 sm:mb-0 sm:ml-4 sm:order-2 overflow-hidden">
                                <img src="{{ asset($unit->image) }}" alt="{{ $unit->name }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <div class="w-full sm:flex-1 sm:order-1">
                                <h2 class="text-base sm:text-lg font-bold text-gray-800">
                                    <a href="{{ $unit->link }}" target="_blank"
                                        class="text-blue-600 hover:underline block">
                                        {{ $unit->name }}
                                    </a>
                                </h2>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>