  <section id="navbar" class="  bg-blue-800 shadow-md">
        <div class="flex items-center justify-between bg-blue-800 px-6 py-4">
            {{-- Logo dan Nama --}}
            <div class="flex items-center">
                <img src="{{ asset('assets/images/download.jpg') }}" alt="Logo" class="h-16 w-16 rounded-full mr-4"
                    loading="lazy" decoding="async" />
                <span class="text-2xl font-sans font-bold text-white">Win-Hunter</span>
            </div>

            {{-- Hamburger Icon --}}
            <div class="md:hidden">
                <button id="menu-toggle"
                    class="text-white focus:outline-none text-3xl transition-transform duration-200 hover:scale-110">
                    &#9776;
                </button>
            </div>

            {{-- Menu Desktop --}}
            <div class="hidden md:block">
                <ul class="flex space-x-6 text-lg font-sans font-semibold">
                    @foreach ([['#home', 'Home'], ['#content', 'Tentang Kami'], ['#coach', 'Pelatih'], ['#jadwal', 'Jadwal Latihan'], ['#contact', 'Kontak & Alamat']] as [$href, $label])
                        <li>
                            <a href="{{ $href }}"
                                class="relative text-white transition-colors duration-200
                                  hover:text-blue-100
                                  after:absolute after:left-0 after:-bottom-1
                                  after:h-[2px] after:w-0 after:bg-white
                                  after:rounded-full after:transition-all after:duration-300
                                  hover:after:w-full">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Overlay Mobile --}}
        <div id="mobile-overlay"
            class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none
                transition-opacity duration-300 md:hidden z-40">
        </div>

        {{-- Mobile Menu (slide-in) --}}
        <div id="mobile-menu"
            class="fixed top-0 right-0 w-64 max-w-[80%] h-full bg-blue-700
                translate-x-full transition-transform duration-300 ease-in-out
                md:hidden z-50 shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b border-blue-600">
                <span class="text-white font-semibold text-lg">Menu</span>
                <button id="menu-close" aria-label="Close menu"
                    class="text-white hover:text-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <ul class="flex flex-col mt-4 px-6 text-white text-base space-y-2">
                @foreach ([['#home', 'Home'], ['#content', 'Tentang Kami'], ['#coach', 'Pelatih'], ['#jadwal', 'Jadwal Latihan'], ['#contact', 'Kontak & Alamat']] as [$href, $label])
                    <li>
                        <a href="{{ $href }}"
                            class="block py-2 rounded-md px-2
                              transition-all duration-200
                              hover:bg-blue-600/70 hover:pl-4">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </section>
