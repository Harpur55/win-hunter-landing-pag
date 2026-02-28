 <section id="service" class="bg-gray-100 py-10 px-4 sm:px-6 lg:px-20">
        <h1 class="text-4xl font-extrabold mb-10 text-center text-gray-900">Kelas Taekwondo</h1>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 my-6 text-center">
            <h2 class=" font-bold text-gray-700 mb-2">Biaya Pendaftaran Start From</h2>
            <h2 class="text-4xl font-extrabold text-green-600">Rp 550.000 <span class="text-gray-600 text-lg">(Sudah
                    Include Seragam Taekwondo)</span></h2>
        </div>
        @php
            $class = DB::table('kelas')->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($kelas as $index => $cls)
                <div
                    class="flex flex-col bg-blue-50 border border-blue-200 rounded-2xl shadow-md overflow-hidden transition-all transform hover:scale-105 duration-300 animate-fade-up h-full">
                    <!-- Gambar -->
                    <img src="{{ Storage::url($cls->image) }}" alt="{{ $cls->name }}"
                        class="w-full object-cover rounded-lg rounded-t-2xl mb-4">
                    <!-- Nama Kelas -->
                    <div
                        class="bg-gradient-to-r from-blue-600 to-blue-800 text-white text-center py-3 px-4 font-bold text-lg tracking-wide">
                        {{ $cls->name }}
                    </div>

                    <!-- Konten -->
                    <div class="flex flex-col justify-between flex-grow p-4">
                        <!-- Accordion (All screen sizes) -->
                        <button type="button" onclick="toggleDescription({{ $index }})"
                            class="flex items-center gap-2 text-sm text-blue-700 font-semibold mb-2 text-left focus:outline-none hover:text-blue-900 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Lihat Benefit
                        </button>

                        <div id="desc-{{ $index }}"
                            class="hidden mb-4 px-3 py-2 bg-blue-100 rounded-lg border border-blue-300 animate-fade-in">
                            @php
                                $points = preg_split('/\r\n|\r|\n/', $cls->description);
                            @endphp
                            @foreach ($points as $point)
                                <p class="text-sm text-gray-700 text-justify">{{ $point }}</p>
                                @if (!$loop->last)
                                    <hr class="my-2 border-gray-300">
                                @endif
                            @endforeach
                        </div>

                        <!-- Tombol CTA -->
                        <a href="https://wa.me/6285890810081?text=Halo%21%20Saya%20tertarik%20dengan%20Taekwondo%20Win-Hunter%20dan%20ingin%20bertanya%20lebih%20lanjut.%20Boleh%20minta%20informasinya%3F"
                            target="_blank" class="mt-auto w-full">
                            <button
                                class="w-full bg-green-600 text-white text-sm font-semibold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors duration-300 inline-flex items-center justify-center gap-2">
                                <!-- Ikon WhatsApp -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current"
                                    viewBox="0 0 32 32">
                                    <path
                                        d="M19.11 17.44c-.28-.14-1.64-.81-1.9-.9s-.45-.14-.64.14-.73.9-.9 1.09-.33.21-.61.07a7.65 7.65 0 01-2.26-1.39 8.45 8.45 0 01-1.56-1.94c-.16-.28 0-.43.12-.57.12-.12.28-.33.42-.5.14-.17.19-.28.28-.47s.05-.36-.02-.5-.64-1.54-.88-2.11c-.23-.56-.47-.49-.64-.5h-.55c-.19 0-.5.07-.76.36s-1 1-1 2.43 1.02 2.82 1.16 3.01 2 3.06 4.85 4.29c.68.29 1.21.46 1.63.59.69.22 1.32.19 1.82.12.56-.08 1.64-.67 1.87-1.32.23-.65.23-1.21.16-1.32-.07-.12-.26-.19-.54-.33z" />
                                    <!-- dipotong untuk ringkas -->
                                </svg>
                                Daftar Sekarang
                            </button>

                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Animasi + Script -->

    </section>