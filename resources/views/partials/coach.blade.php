  <section id="coach" class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-50 to-blue-500">
        <div class="container mx-auto flex flex-col items-center max-w-6xl">
            <h2
                class="font-bold text-3xl sm:text-4xl lg:text-5xl bg-gradient-to-r from-gray-900 to-emerald-800 bg-clip-text text-transparent mb-10 sm:mb-14 text-center">
                Pelatih
            </h2>

            <!-- DESKTOP: Grid 5 kolom -->
            <div class="w-full max-w-6xl px-2 sm:px-4 mb-6 hidden lg:grid grid-cols-5 gap-6">
                @foreach ($coaches as $coach)
                    @php
                        if (str_starts_with($coach->foto, 'assets/')) {
                            $foto = asset($coach->foto);
                        } else {
                            $foto = asset('storage/' . $coach->foto);
                        }
                    @endphp

                    <div
                        class="group bg-white/80 backdrop-blur-sm p-5 rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-emerald-500/20 border border-white/50 hover:border-emerald-300 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center h-80">
                        <div class="relative mb-4 w-28 h-28 mx-auto">
                            <img class="object-cover w-full h-full rounded-2xl border-4 border-white shadow-xl group-hover:shadow-2xl group-hover:scale-110 transition-all duration-500 ring-2 ring-transparent group-hover:ring-emerald-400/50"
                                src="{{ $foto }}?v={{ $coach->updated_at->timestamp }}"
                                alt="{{ $coach->nama }}" loading="lazy">
                        </div>
                        <h4
                            class="font-bold text-lg text-gray-900 mb-1.5 group-hover:text-emerald-700 transition-colors">
                            {{ $coach->nama }}
                        </h4>
                        <p class="text-emerald-600 font-semibold mb-2 px-3 py-0.5 bg-emerald-100 rounded-full text-xs">
                            {{ $coach->sabuk }}
                        </p>
                        <p class="text-gray-600 font-medium mb-4 text-xs">
                            {{ $coach->role }}
                        </p>
                        @php
                            $doc = $coach->documents->first();
                        @endphp

                        @if ($doc)
                            <button type="button" data-doc="{{ asset('storage/' . $doc->document) }}"
                                onclick="openDocModal(this)"
                                class="group/btn inline-flex items-center gap-2 px-5 py-2 font-semibold text-xs bg-white text-gray-900 rounded-xl border border-gray-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 active:bg-emerald-600 active:scale-95 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                                <span class="tracking-wide">Lihat Sertifikat</span>
                                <svg class="w-4 h-4 opacity-70 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @else
                            <span class="text-xs text-gray-400 italic">Dokumen belum ada</span>
                        @endif
                    </div>
                @endforeach
            </div>


            <div class="w-full max-w-sm sm:max-w-md lg:hidden">
                <div class="swiper coach-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($coaches as $coach)
                            @php
                                if (str_starts_with($coach->foto, 'assets/')) {
                                    $foto = asset($coach->foto);
                                } else {
                                    $foto = asset('storage/' . $coach->foto);
                                }
                            @endphp

                            <div class="swiper-slide px-1">
                                <div
                                    class="bg-white shadow-xl rounded-3xl px-5 py-6 flex flex-col items-center text-center mb-4">
                                    <div class="relative mb-4 w-32 h-32 mx-auto">
                                        <img class="object-cover w-full h-full rounded-2xl border-4 border-white shadow-lg"
                                            src="{{ $foto }}?v={{ $coach->updated_at->timestamp }}"
                                            alt="{{ $coach->nama }}" loading="lazy">
                                    </div>

                                    <h4 class="font-bold text-base text-gray-900 mb-1">
                                        {{ $coach->nama }}
                                    </h4>

                                    <p
                                        class="inline-flex items-center justify-center text-emerald-600 font-semibold mb-1 px-3 py-0.5 bg-emerald-100 rounded-full text-[11px]">
                                        {{ $coach->sabuk }}
                                    </p>

                                    <p class="text-gray-600 text-xs mb-4">
                                        {{ $coach->role }}
                                    </p>
                                    @php
                                        $doc = $coach->documents->first();
                                    @endphp

                                    @if ($doc)
                                        <button type="button" data-doc="{{ asset('storage/' . $doc->document) }}"
                                            onclick="openDocModal(this)"
                                            class="group/btn inline-flex items-center gap-2 px-5 py-2 font-semibold text-xs bg-white text-gray-900 rounded-xl border border-gray-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 active:bg-emerald-600 active:scale-95 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                                            <span class="tracking-wide">Lihat Sertifikat</span>
                                            <svg class="w-4 h-4 opacity-70 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Dokumen belum ada</span>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination sedikit menjauh dari card -->

                </div>
            </div>
        </div>
    </section>