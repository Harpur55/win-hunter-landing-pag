<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Win-Hunter.com</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/download.jpg') }} " class="rounded-full " />
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <style>
        /* Hilangkan scrollbar horizontal */
        html,
        body {
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans scroll-smooth">
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

    <section class="bg-blue-700 py-10 px-6 md:px-20" id="home">

        <canvas id="particleCanvas" class="absolute inset-0 w-full h-full pointer-events-none">
        </canvas>

        <div class="container mx-auto flex flex-col-reverse md:flex-row items-center md:h-screen">
            <!-- TEXT AREA -->
            <div class="w-full md:w-1/2 text-center md:text-left mt-3 pt-3 md:pt-0">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4"> SACTI CLUB<br> WIN-HUNTER </h1>
                <p class="text-2xl md:text-4xl text-white mb-6"> Mental, Instinct, Technique </p>
                <a href="https://wa.me/6285890810081?text=Halo%21%20%F0%9F%91%8B%20Saya%20tertarik%20dengan%20Taekwondo%20Win-Hunter%20dan%20ingin%20bertanya%20lebih%20lanjut.%20Boleh%20minta%20informasinya%3F%20%F0%9F%99%8F

             
             "
                    target="_blank"
                    class="inline-block bg-blue-600 text-white text-lg md:text-xl px-6 py-3 rounded-lg shadow hover:bg-orange-700 transition">
                    Join Sekarang! </a>
            </div>
            <!-- IMAGE AREA -->
            <div class="w-full md:w-1/2 flex justify-center items-center relative mb-4 md:mb-0 h-auto md:h-full mt-3">
                <img src="{{ asset('assets/images/new-logo-win-hunter.png') }}" alt="logo Win-Hunter"
                    class="w-[200px] h-[200px] sm:w-[300px] sm:h-[300px] md:w-[430px] md:h-[500px] md:absolute md:top-1/2 md:left-1/2 md:transform md:-translate-x-1/2 md:-translate-y-1/2 object-cover" />
            </div>
        </div>
    </section>





    <div id="galleryModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center transition">

        <div class="relative max-w-5xl w-full px-4">

            <button type="button" class="absolute -top-10 right-4 text-white text-2xl" onclick="closeGalleryModal()">
                ✕
            </button>

            <img id="galleryModalImage" src="" alt="Galeri"
                class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">

            <h4 id="galleryModalTitle" class="text-center text-white mt-4 text-lg font-semibold">
            </h4>
        </div>
    </div>






















   
    <footer class="bg-blue-800 text-white py-6">
        <div class="container mx-auto px-4">
            <div
                class="flex flex-col sm:flex-row items-center justify-center sm:justify-center gap-3 text-center sm:text-center">
                <!-- Logo -->
                <img src="{{ asset('assets/images/download.jpg') }}" alt="Logo" class="h-16 w-16 rounded-full">

                <!-- Teks -->
                <p class="text-lg">
                    <span class="block sm:inline">Win-Hunter since 2015.</span>
                    <span class="block sm:inline">Mental, Instinct, Technique</span>
                </p>
            </div>
        </div>
    </footer>
</body>


</html>
