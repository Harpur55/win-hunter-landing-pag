<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
            <img src="{{ asset('assets/images/download.jpg') }}" alt="Logo"
                 class="h-16 w-16 rounded-full mr-4" />
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
                @foreach ([
                    ['#home','Home'],
                    ['#content','Tentang Kami'],
                    ['#coach','Pelatih'],
                    ['#jadwal','Jadwal Latihan'],
                    ['#contact','Kontak & Alamat'],
                ] as [$href,$label])
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
                transition-opacity duration-300 md:hidden z-40"></div>

    {{-- Mobile Menu (slide-in) --}}
    <div id="mobile-menu"
         class="fixed top-0 right-0 w-64 max-w-[80%] h-full bg-blue-700
                translate-x-full transition-transform duration-300 ease-in-out
                md:hidden z-50 shadow-xl">
        <div class="flex justify-between items-center px-6 py-4 border-b border-blue-600">
            <span class="text-white font-semibold text-lg">Menu</span>
            <button id="menu-close" aria-label="Close menu"
                    class="text-white hover:text-gray-200 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <ul class="flex flex-col mt-4 px-6 text-white text-base space-y-2">
            @foreach ([
                ['#home','Home'],
                ['#content','Tentang Kami'],
                ['#coach','Pelatih'],
                ['#jadwal','Jadwal Latihan'],
                ['#contact','Kontak & Alamat'],
            ] as [$href,$label])
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

          <canvas
    id="particleCanvas"
    class="absolute inset-0 w-full h-full pointer-events-none">
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

   <section id="unit" class="bg-white py-8 px-2">
  <h1 class="text-4xl font-bold mb-6 text-center text-black">Unit</h1>

  <div class="relative overflow-hidden">
    <div
      id="unit-slider-wrapper"
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

            <div
              class="w-20 h-20 rounded-md border mb-3 sm:mb-0 sm:ml-4 sm:order-2 overflow-hidden">
              <img
                src="{{ asset($unit->image) }}"
                alt="{{ $unit->name }}"
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


  <section id="galery" class="bg-white py-10 px-4 sm:px-6 lg:px-20 bg-gradient-to-br from-emerald-50 to-blue-500 ">
  <div class="container mx-auto">
    <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">
      Galeri
    </h2>
    <p class="text-md sm:text-xl text-gray-700 text-center mb-10">
      Beberapa momen berharga dari kegiatan kami.
    </p>

    <!-- Swiper Container -->
    <div class="swiper galerySwiper">
      <div class="swiper-wrapper">
        @foreach ($galleries as $gallery)
          <div class="swiper-slide">
            <div class="bg-gradient-to-br from-amber-50/70 via-white to-slate-50 rounded-3xl border border-amber-100/40 shadow-md p-4 sm:p-6">
              {{-- Judul galeri --}}
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 text-center">
                {{ $gallery->title }}
              </h3>

              {{-- Grid foto dalam 1 slide --}}
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                @foreach ($gallery->images_url as $image)
                  <button
                    type="button"
                    class="relative group"
                    onclick="openGalleryModal('{{ $image }}', '{{ addslashes($gallery->title) }}')"
                  >
                    <img
                      src="{{ $image }}"
                      alt="{{ $gallery->title }}"
                      class="w-full h-40 sm:h-48 object-contain rounded-xl shadow-sm group-hover:opacity-90 group-hover:scale-[1.02] transition-all duration-200"
                    >
                    <span
                      class="absolute inset-0 rounded-xl ring-2 ring-emerald-400/0 group-hover:ring-emerald-400/70 transition-all duration-200">
                    </span>
                  </button>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Navigasi -->
      <div class="swiper-pagination mt-4"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</section>
<div id="galleryModal"
     class="fixed inset-0 bg-black/70 z-50 items-center justify-center hidden">
  <div class="max-w-3xl w-full px-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-2xl relative">
      <button type="button"
              class="absolute top-3 right-3 bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm"
              onclick="closeGalleryModal()">
        ✕
      </button>

      <img id="galleryModalImage"
           src=""
           alt="Galeri"
           class="w-full max-h-[80vh] object-contain bg-black">

      <div class="px-4 py-3 border-t text-center">
        <h4 id="galleryModalTitle" class="text-sm sm:text-base font-semibold text-gray-800"></h4>
      </div>
    </div>
  </div>
</div>

  <section id="about-us" class="relative bg-gradient-to-b from-blue-50 to-white py-16 sm:py-20">
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-blue-600/40 to-transparent pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8">
        {{-- Heading --}}
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold tracking-[0.2em] text-blue-600 uppercase">
                About Us
            </h2>
            <p class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">
                Mental, Instinct, Technique
            </p>
            <p class="mt-4 text-md sm:text-base text-gray-600">
                Win-Hunter hadir sebagai wadah pembinaan Taekwondo yang mengutamakan teknik,
                sportivitas, dan pengembangan karakter generasi muda.
            </p>
        </div>

        {{-- Content --}}
        <div class="mt-10 flex flex-col items-center">
            {{-- Kolom teks utama --}}
            <div class="space-y-6 text-center lg:text-left max-w-xl">
                <p class="text-md sm:text-base text-gray-700 leading-relaxed">
                    Berawal dari komunitas kecil, Win-Hunter berkembang menjadi klub Taekwondo
                    yang aktif mengikuti berbagai kejuaraan regional hingga internasional.
                    Latihan dirancang bertahap sehingga cocok untuk pemula hingga atlet prestasi.
                </p>
                <p class="text-md sm:text-base text-gray-700 leading-relaxed">
                    Dengan pelatih berlisensi dan kurikulum yang terstruktur, setiap sesi
                    latihan tidak hanya fokus pada fisik, tetapi juga disiplin, rasa percaya
                    diri, dan sikap hormat kepada sesama.
                </p>

                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-xl mx-auto lg:mx-0">
    <div
        class="rounded-xl border border-blue-300 bg-gradient-to-br from-blue-90 to-white
               px-4 py-4 shadow-sm">
        <dt class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
            Berdiri Sejak
        </dt>
        <dd class="mt-1 text-lg font-bold text-gray-900">
            2015
        </dd>
    </div>

    <div
        class="rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white
               px-4 py-4 shadow-sm">
        <dt class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">
            Atlet Aktif
        </dt>
        <dd class="mt-1 text-lg font-bold text-gray-900">
            100+
        </dd>
    </div>

    <div
        class="rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-white
               px-4 py-4 shadow-sm">
        <dt class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">
            Medali Kejuaraan
        </dt>
        <dd class="mt-1 text-lg font-bold text-gray-900">
            100+
        </dd>
    </div>
</dl>

            </div>

            {{-- Kolom kedua (opsional, misalnya gambar) --}}
        </div>
    </div>
</section>


<section id="coach" class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-50 to-blue-500">
  <div class="container mx-auto flex flex-col items-center max-w-6xl">
    <h2 class="font-bold text-3xl sm:text-4xl lg:text-5xl bg-gradient-to-r from-gray-900 to-emerald-800 bg-clip-text text-transparent mb-10 sm:mb-14 text-center">
      Pelatih
    </h2>

    <!-- DESKTOP: Grid 5 kolom -->
    <div class="w-full max-w-6xl px-2 sm:px-4 mb-6 hidden lg:grid grid-cols-5 gap-6">
      @foreach($coaches as $coach)
        @php
          if (str_starts_with($coach->foto, 'assets/')) {
              $foto = asset($coach->foto);
          } else {
              $foto = asset('storage/' . $coach->foto);
          }
        @endphp

        <div class="group bg-white/80 backdrop-blur-sm p-5 rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-emerald-500/20 border border-white/50 hover:border-emerald-300 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center h-80">
          <div class="relative mb-4 w-28 h-28 mx-auto">
            <img
              class="object-cover w-full h-full rounded-2xl border-4 border-white shadow-xl group-hover:shadow-2xl group-hover:scale-110 transition-all duration-500 ring-2 ring-transparent group-hover:ring-emerald-400/50"
              src="{{ $foto }}?v={{ $coach->updated_at->timestamp }}"
              alt="{{ $coach->nama }}"
              loading="lazy"
            >
          </div>
          <h4 class="font-bold text-lg text-gray-900 mb-1.5 group-hover:text-emerald-700 transition-colors">
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
  <button
    type="button"
    data-doc="{{ asset('storage/' . $doc->document) }}"
    onclick="openDocModal(this)"
    class="group/btn inline-flex items-center gap-2 px-5 py-2 font-semibold text-xs bg-white text-gray-900 rounded-xl border border-gray-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 active:bg-emerald-600 active:scale-95 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300"
  >
    <span class="tracking-wide">Lihat Sertifikat</span>
    <svg class="w-4 h-4 opacity-70 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all duration-300"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
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
          @foreach($coaches as $coach)
            @php
              if (str_starts_with($coach->foto, 'assets/')) {
                  $foto = asset($coach->foto);
              } else {
                  $foto = asset('storage/' . $coach->foto);
              }
            @endphp

            <div class="swiper-slide px-1">
              <div class="bg-white shadow-xl rounded-3xl px-5 py-6 flex flex-col items-center text-center mb-4">
                <div class="relative mb-4 w-32 h-32 mx-auto">
                  <img
                    class="object-cover w-full h-full rounded-2xl border-4 border-white shadow-lg"
                    src="{{ $foto }}?v={{ $coach->updated_at->timestamp }}"
                    alt="{{ $coach->nama }}"
                    loading="lazy"
                  >
                </div>

                <h4 class="font-bold text-base text-gray-900 mb-1">
                  {{ $coach->nama }}
                </h4>

                <p class="inline-flex items-center justify-center text-emerald-600 font-semibold mb-1 px-3 py-0.5 bg-emerald-100 rounded-full text-[11px]">
                  {{ $coach->sabuk }}
                </p>

                <p class="text-gray-600 text-xs mb-4">
                  {{ $coach->role }}
                </p>
                @php
  $doc = $coach->documents->first();
@endphp

@if ($doc)
  <button
    type="button"
    data-doc="{{ asset('storage/' . $doc->document) }}"
    onclick="openDocModal(this)"
    class="group/btn inline-flex items-center gap-2 px-5 py-2 font-semibold text-xs bg-white text-gray-900 rounded-xl border border-gray-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 active:bg-emerald-600 active:scale-95 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300"
  >
    <span class="tracking-wide">Lihat Sertifikat</span>
    <svg class="w-4 h-4 opacity-70 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all duration-300"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
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

<div id="docModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

  <div class="relative w-full h-full flex items-center justify-center p-4">
    <div class="relative bg-black rounded-xl w-full max-w-4xl h-[85vh] shadow-2xl overflow-hidden">

      <!-- CLOSE -->
      <button onclick="closeDocModal()"
        class="absolute top-3 right-3 z-50 text-white bg-black/60 hover:bg-black px-3 py-1 rounded-lg text-sm">
        ✕ Tutup
      </button>

      <!-- PREVIEW -->
      <iframe
        id="docFrame"
        class="w-full h-full"
        src=""
        frameborder="0"
        sandbox="allow-scripts allow-same-origin">
      </iframe>

      <!-- WATERMARK -->
      <div class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-10 text-white text-4xl font-bold rotate-[-30deg] select-none">
        WIN HUNTER
      </div>

    </div>
  </div>
</div>



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
                   <img 
    src="{{ Storage::url($cls->image) }}" 
    alt="{{ $cls->name }}"
    class="w-full h-45 object-cover rounded-t-2xl mb-4">
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
                                    <path d="..." /> <!-- dipotong untuk ringkas -->
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


    <section id="jadwal" class="bg-gradient-to-br from-emerald-50 to-blue-500 py-10 px-4 sm:px-6 lg:px-20">
        <div class="container mx-auto">
            <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Jadwal Latihan Pusat Dojang
                Waterland Metland Cileungsi</h2>
            <p class="text-md sm:text-xl text-gray-700 text-center mb-10"> Latihan rutin diadakan setiap hari dari
                Senin sampai Minggu. </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> @php $jadwal = [ ['hari' => 'Senin', 'kelas' => [['Kelas Prestasi', '16:00 - 17:45WIB']]], ['hari' => 'Selasa', 'kelas' => [['Kelas Prestasi', '16:00 - 17:45WIB']]], ['hari' => 'Rabu', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 17:45WIB' ]]], ['hari'=> 'Kamis', 'kelas' => [['Kelas Reguler > 12 tahun', '16:00 - 17:45WIB']]], ['hari' => 'Jumat', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 17:45WIB' ]]], ['hari'=> 'Sabtu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '08:00 - 10:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ], ['hari' => 'Minggu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '07:30 - 09:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ] ]; @endphp @foreach ($jadwal as $j)
                    <div
                        class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 min-h-[200px] flex flex-col justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-blue-700 mb-4">{{ $j['hari'] }}</h3>
                            @foreach ($j['kelas'] as $kelas)
                                <div class="mb-4">
                                    <p class="text-gray-800 font-semibold text-3xl ">{{ $kelas[0] }}</p>
                                    <p class="text-gray-600 text-2xl">{{ $kelas[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

<<<<<<< HEAD
    <section id="galery" class="bg-white py-10 px-4 sm:px-6 lg:px-20 bg-gradient-to-br from-emerald-50 to-blue-500 ">
  <div class="container mx-auto">
    <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">
      Galeri
    </h2>
    <p class="text-md sm:text-xl text-gray-700 text-center mb-10">
      Beberapa momen berharga dari kegiatan kami.
    </p>

    <!-- Swiper Container -->
    <div class="swiper galerySwiper">
      <div class="swiper-wrapper">
        @foreach ($galleries as $gallery)
          <div class="swiper-slide">
            <div class="bg-gradient-to-br from-amber-50/70 via-white to-slate-50 rounded-3xl border border-amber-100/40 shadow-md p-4 sm:p-6">
              {{-- Judul galeri --}}
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 text-center">
                {{ $gallery->title }}
              </h3>

              {{-- Grid foto dalam 1 slide --}}
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                @foreach ($gallery->images_url as $image)
                  <button
                    type="button"
                    class="relative group"
                    onclick="openGalleryModal('{{ $image }}', '{{ addslashes($gallery->title) }}')"
                  >
                    <img
                      src="{{ $image }}"
                      alt="{{ $gallery->title }}"
                      class="w-full h-40 sm:h-48 object-contain rounded-xl shadow-sm group-hover:opacity-90 group-hover:scale-[1.02] transition-all duration-200"
                    >
                    <span
                      class="absolute inset-0 rounded-xl ring-2 ring-emerald-400/0 group-hover:ring-emerald-400/70 transition-all duration-200">
                    </span>
                  </button>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Navigasi -->
      <div class="swiper-pagination mt-4"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</section>
<div id="galleryModal"
     class="fixed inset-0 bg-black/70 z-50 items-center justify-center hidden">
  <div class="max-w-3xl w-full px-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-2xl relative">
      <button type="button"
              class="absolute top-3 right-3 bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm"
              onclick="closeGalleryModal()">
        ✕
      </button>

      <img id="galleryModalImage"
           src=""
           alt="Galeri"
           class="w-full max-h-[80vh] object-contain bg-black">

      <div class="px-4 py-3 border-t text-center">
        <h4 id="galleryModalTitle" class="text-sm sm:text-base font-semibold text-gray-800"></h4>
      </div>
    </div>
  </div>
</div>
=======
   
>>>>>>> 041e11e (refactor login and update)



    <section class="bg-gradient-to-br from-emerald-50 to-yellow-100" id="contact">
        <div class="container px-6 py-12 mx-auto">
            <div class="text-center">
                <h3 class="font-semibold text-3xl text-black">Hubungi kami</h3>
                <p class="mt-3 text-gray-500">Bergabung Bersama Kami</p>
            </div>
            <!-- Kontak Section -->
            <div class="flex flex-col lg:flex-row justify-center items-center gap-12 mt-10">
                <!-- Email -->
                <div class="text-center lg:text-left">
                    <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
                        <!-- Email Icon -->
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <h2 class="mt-4 text-lg font-medium text-gray-800">Email</h2>
                    <p class="mt-2 text-sm text-black">Mari Hubungi kami</p>
                    <p class="mt-2 text-sm text-blue-500">win.hunter1601@gmail.com</p>
                </div>
                <!-- Office -->
                <div class="text-center lg:text-left">
                    <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
                        <!-- Office Icon -->
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 70 70">
                            <radialGradient id="TGwjmZMm2W~B4yrgup6jda_119026_gr1" cx="32" cy="32.5"
                                r="31.259" gradientTransform="matrix(1 0 0 -1 0 64)" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#efdcb1"></stop>
                                <stop offset="0" stop-color="#f2e0bb"></stop>
                                <stop offset=".011" stop-color="#f2e0bc"></stop>
                                <stop offset=".362" stop-color="#f9edd2"></stop>
                                <stop offset=".699" stop-color="#fef4df"></stop>
                                <stop offset="1" stop-color="#fff7e4"></stop>
                            </radialGradient>
                            <path fill="url(#TGwjmZMm2W~B4yrgup6jda_119026_gr1)"
                                d="M58,54c-1.1,0-2-0.9-2-2s0.9-2,2-2h2.5c1.9,0,3.5-1.6,3.5-3.5S62.4,43,60.5,43H50c-1.4,0-2.5-1.1-2.5-2.5	S48.6,38,50,38h8c1.7,0,3-1.3,3-3s-1.3-3-3-3H42v-6h18c2.3,0,4.2-2,4-4.4c-0.2-2.1-2.1-3.6-4.2-3.6H58c-1.1,0-2-0.9-2-2s0.9-2,2-2	h0.4c1.3,0,2.5-0.9,2.6-2.2c0.2-1.5-1-2.8-2.5-2.8h-14C43.7,9,43,8.3,43,7.5S43.7,6,44.5,6h3.9c1.3,0,2.5-0.9,2.6-2.2	C51.1,2.3,50,1,48.5,1H15.6c-1.3,0-2.5,0.9-2.6,2.2C12.9,4.7,14,6,15.5,6H19c1.1,0,2,0.9,2,2s-0.9,2-2,2H6.2c-2.1,0-4,1.5-4.2,3.6	C1.8,16,3.7,18,6,18h2.5c1.9,0,3.5,1.6,3.5,3.5S10.4,25,8.5,25H5.2c-2.1,0-4,1.5-4.2,3.6C0.8,31,2.7,33,5,33h17v11H6	c-1.7,0-3,1.3-3,3s1.3,3,3,3l0,0c1.1,0,2,0.9,2,2s-0.9,2-2,2H4.2c-2.1,0-4,1.5-4.2,3.6C-0.2,60,1.7,62,4,62h53.8	c2.1,0,4-1.5,4.2-3.6C62.2,56,60.3,54,58,54z">
                            </path>
                            <radialGradient id="TGwjmZMm2W~B4yrgup6jdb_119026_gr2" cx="18.51" cy="66.293"
                                r="69.648" gradientTransform="matrix(.6435 -.7654 .5056 .4251 -26.92 52.282)"
                                gradientUnits="userSpaceOnUse">
                                <stop offset=".073" stop-color="#eacc7b"></stop>
                                <stop offset=".184" stop-color="#ecaa59"></stop>
                                <stop offset=".307" stop-color="#ef802e"></stop>
                                <stop offset=".358" stop-color="#ef6d3a"></stop>
                                <stop offset=".46" stop-color="#f04b50"></stop>
                                <stop offset=".516" stop-color="#f03e58"></stop>
                                <stop offset=".689" stop-color="#db359e"></stop>
                                <stop offset=".724" stop-color="#ce37a4"></stop>
                                <stop offset=".789" stop-color="#ac3cb4"></stop>
                                <stop offset=".877" stop-color="#7544cf"></stop>
                                <stop offset=".98" stop-color="#2b4ff2"></stop>
                            </radialGradient>
                            <path fill="url(#TGwjmZMm2W~B4yrgup6jdb_119026_gr2)"
                                d="M45,57H19c-5.5,0-10-4.5-10-10V21c0-5.5,4.5-10,10-10h26c5.5,0,10,4.5,10,10v26C55,52.5,50.5,57,45,57z">
                            </path>
                            <path fill="#fff"
                                d="M32,20c4.6,0,5.1,0,6.9,0.1c1.7,0.1,2.6,0.4,3.2,0.6c0.8,0.3,1.4,0.7,2,1.3c0.6,0.6,1,1.2,1.3,2 c0.2,0.6,0.5,1.5,0.6,3.2C46,28.9,46,29.4,46,34s0,5.1-0.1,6.9c-0.1,1.7-0.4,2.6-0.6,3.2c-0.3,0.8-0.7,1.4-1.3,2 c-0.6,0.6-1.2,1-2,1.3c-0.6,0.2-1.5,0.5-3.2,0.6C37.1,48,36.6,48,32,48s-5.1,0-6.9-0.1c-1.7-0.1-2.6-0.4-3.2-0.6 c-0.8-0.3-1.4-0.7-2-1.3c-0.6-0.6-1-1.2-1.3-2c-0.2-0.6-0.5-1.5-0.6-3.2C18,39.1,18,38.6,18,34s0-5.1,0.1-6.9 c0.1-1.7,0.4-2.6,0.6-3.2c0.3-0.8,0.7-1.4,1.3-2c0.6-0.6,1.2-1,2-1.3c0.6-0.2,1.5-0.5,3.2-0.6C26.9,20,27.4,20,32,20 M32,17 c-4.6,0-5.2,0-7,0.1c-1.8,0.1-3,0.4-4.1,0.8c-1.1,0.4-2.1,1-3,2s-1.5,1.9-2,3c-0.4,1.1-0.7,2.3-0.8,4.1C15,28.8,15,29.4,15,34 s0,5.2,0.1,7c0.1,1.8,0.4,3,0.8,4.1c0.4,1.1,1,2.1,2,3c0.9,0.9,1.9,1.5,3,2c1.1,0.4,2.3,0.7,4.1,0.8c1.8,0.1,2.4,0.1,7,0.1 s5.2,0,7-0.1c1.8-0.1,3-0.4,4.1-0.8c1.1-0.4,2.1-1,3-2c0.9-0.9,1.5-1.9,2-3c0.4-1.1,0.7-2.3,0.8-4.1c0.1-1.8,0.1-2.4,0.1-7 s0-5.2-0.1-7c-0.1-1.8-0.4-3-0.8-4.1c-0.4-1.1-1-2.1-2-3s-1.9-1.5-3-2c-1.1-0.4-2.3-0.7-4.1-0.8C37.2,17,36.6,17,32,17L32,17z">
                            </path>
                            <path fill="#fff"
                                d="M32,25c-5,0-9,4-9,9s4,9,9,9s9-4,9-9S37,25,32,25z M32,40c-3.3,0-6-2.7-6-6s2.7-6,6-6s6,2.7,6,6S35.3,40,32,40 z">
                            </path>
                            <circle cx="41" cy="25" r="2" fill="none"></circle>
                        </svg>
                    </span>
                    <h2 class="mt-4 text-lg font-medium text-gray-800">Sosial Media</h2>
                    <p class="mt-2 text-sm text-black">Follow Kami</p>
                    <p class="mt-2 text-sm text-blue-500">
                        <a
                            href="https://www.instagram.com/sactiwinhunter?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">@sactiwinhunter
                    </p>
                    </a>
                </div>
                <!-- Phone -->
                <div class="text-center lg:text-left">
    {{-- Icon phone kecil --}}
    <span class="inline-flex items-center justify-center p-2 rounded-full bg-blue-100 text-blue-600">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
            <path
                d="M3 5.5C3 4.12 4.12 3 5.5 3h1.3c.7 0 1.31.4 1.6 1.02l1.03 2.24a1.8 1.8 0 01-.4 1.95l-1.02 1.02a10.5 10.5 0 004.9 4.9l1.02-1.02c.52-.52 1.3-.68 1.95-.4l2.24 1.03c.62.29 1.02.9 1.02 1.6v1.3A2.5 2.5 0 0118.5 21C9.93 21 3 14.07 3 5.5z"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            />
        </svg>
    </span>

    <h2 class="mt-3 text-base font-medium text-gray-800">Phone</h2>
    <p class="mt-1 text-sm text-black">Kontak Kami</p>

    {{-- Tombol WA normal (desktop / tablet) --}}
    <a href="https://wa.me/6285890810081?text=Halo..."
       target="_blank"
       class="hidden md:inline-flex items-center px-3 py-1.5 mt-2
              text-xs sm:text-sm bg-green-500 text-white rounded-md
              hover:bg-green-600 transition">
        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M20.52 3.48A11.85 11.85 0 0012.04 0C5.39 0 .04 5.35.04 11.96c0 2.1.55 4.17 1.6 5.99L0 24l6.25-1.63a12.03 12.03 0 005.79 1.47h.01c6.65 0 12.04-5.35 12.04-11.96a11.87 11.87 0 00-3.57-8.4zM12.04 22a9.92 9.92 0 01-5.06-1.37l-.36-.21-3.71.97.99-3.6-.24-.38a9.93 9.93 0 01-1.5-5.25c0-5.47 4.49-9.92 10.01-9.92 2.67 0 5.18 1.04 7.07 2.92a9.88 9.88 0 012.94 7.05c0 5.47-4.49 9.92-10.08 9.92zm5.5-7.57c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15s-.78.96-.95 1.16c-.17.2-.35.22-.65.07a8.09 8.09 0 01-2.39-1.48 9.01 9.01 0 01-1.66-2.06c-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.65-.93-2.26-.24-.58-.49-.5-.68-.51l-.58-.01c-.2 0-.52.07-.8.38-.28.3-1.06 1.03-1.06 2.5s1.09 2.9 1.24 3.1c.15.2 2.15 3.28 5.22 4.6.73.31 1.3.49 1.75.62.74.23 1.4.2 1.93.12.59-.09 1.77-.72 2.02-1.41.25-.7.25-1.3.17-1.42-.07-.12-.26-.19-.55-.33z"
            />
        </svg>
        <span>Chat via WhatsApp</span>
    </a>
</div>
<div>
{{-- Floating WA (mobile only) --}}
<a href="https://wa.me/6285890810081?text=Halo..."
   target="_blank"
   class="md:hidden fixed right-4 bottom-4 z-50
          flex items-center justify-center
          w-12 h-12 rounded-full bg-green-500 text-white
          shadow-lg shadow-green-500/40
          hover:bg-green-600 transition">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path
            d="M20.52 3.48A11.85 11.85 0 0012.04 0C5.39 0 .04 5.35.04 11.96c0 2.1.55 4.17 1.6 5.99L0 24l6.25-1.63a12.03 12.03 0 005.79 1.47h.01c6.65 0 12.04-5.35 12.04-11.96a11.87 11.87 0 00-3.57-8.4zM12.04 22a9.92 9.92 0 01-5.06-1.37l-.36-.21-3.71.97.99-3.6-.24-.38a9.93 9.93 0 01-1.5-5.25c0-5.47 4.49-9.92 10.01-9.92 2.67 0 5.18 1.04 7.07 2.92a9.88 9.88 0 012.94 7.05c0 5.47-4.49 9.92-10.08 9.92zm5.5-7.57c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15s-.78.96-.95 1.16c-.17.2-.35.22-.65.07a8.09 8.09 0 01-2.39-1.48 9.01 9.01 0 01-1.66-2.06c-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.65-.93-2.26-.24-.58-.49-.5-.68-.51l-.58-.01c-.2 0-.52.07-.8.38-.28.3-1.06 1.03-1.06 2.5s1.09 2.9 1.24 3.1c.15.2 2.15 3.28 5.22 4.6.73.31 1.3.49 1.75.62.74.23 1.4.2 1.93.12.59-.09 1.77-.72 2.02-1.41.25-.7.25-1.3.17-1.42-.07-.12-.26-.19-.55-.33z"
        />
    </svg>
</a>
</div>
            </div>
                 <!-- Maps Section -->
            <div class="mt-16">
                <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Maps Lokasi Latihan</h2>
                <div class="w-full flex justify-center">
                    <iframe class="w-full lg:w-[75%] h-96 rounded-lg" frameborder="0" title="map" scrolling="no"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.9710922187955!2d106.97513277353566!3d-6.397726762570561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6995fdca64783f%3A0xfedb580d6d020f3a!2sWaterland%20Metland%20Transyogi!5e0!3m2!1sen!2sid!4v1749525799981!5m2!1sen!2sid"
                        width="100" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
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
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.mySwiper', {
        loop: true,
        spaceBetween: 20,
        slidesPerView: 1,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        // navigation: {
        //   nextEl: '.swiper-button-next',
        //   prevEl: '.swiper-button-prev',
        // },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 5,
            },
        },
    });
</script>


<script>
    function toggleDescription(index) {
        const el = document.getElementById(`desc-${index}`);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            el.classList.add('animate-fade-in');
        } else {
            el.classList.add('hidden');
            el.classList.remove('animate-fade-in');
        }
    }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.coach-swiper', {
      slidesPerView: 1,
      spaceBetween: 16,
      loop: true,
      grabCursor: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  });
</script>


<script>
  // Swiper
  document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.galerySwiper', {
      slidesPerView: 1,
      spaceBetween: 8,
      loop: false,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
          on: {
      init() {
        getActiveGalleryId(this);
      },
      slideChange() {
        getActiveGalleryId(this);
      }
    }
  });

  function getActiveGalleryId(swiper) {
    const activeSlide = swiper.slides[swiper.activeIndex];
    const activeGalleryId = activeSlide.dataset.galleryId;

    console.log('Galeri aktif ID:', activeGalleryId);

    // 👉 contoh: simpan ke global
    window.activeGalleryId = activeGalleryId;
  }
    });
  

  // Modal logic
  function openGalleryModal(src, title) {
    const modal = document.getElementById('galleryModal');
    const img   = document.getElementById('galleryModalImage');
    const text  = document.getElementById('galleryModalTitle');

    img.src = src;
    text.textContent = title;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  // Tutup modal jika klik area gelap
  document.getElementById('galleryModal').addEventListener('click', function (e) {
    if (e.target === this) {
      closeGalleryModal();
    }
  });
</script>

 <script>
        const btnOpen   = document.getElementById('menu-toggle');
        const btnClose  = document.getElementById('menu-close');
        const menu      = document.getElementById('mobile-menu');
        const overlay   = document.getElementById('mobile-overlay');
        const links     = menu.querySelectorAll('a');

        function openMenu() {
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('pointer-events-none');
            overlay.classList.add('opacity-100');
        }

        function closeMenu() {
            menu.classList.add('translate-x-full');
            overlay.classList.add('pointer-events-none');
            overlay.classList.remove('opacity-100');
        }

        btnOpen?.addEventListener('click', openMenu);
        btnClose?.addEventListener('click', closeMenu);
        overlay?.addEventListener('click', closeMenu);
        links.forEach(link => link.addEventListener('click', closeMenu));
    </script>

<script>
function openDocModal(btn) {
  const modal = document.getElementById('docModal');
  const frame = document.getElementById('docFrame');

  frame.src = btn.dataset.doc;
  modal.classList.remove('hidden');

  // Disable right click
  document.addEventListener('contextmenu', blockContextMenu);

  // Block key screenshot (PrintScreen, Ctrl+P, Ctrl+S)
  document.addEventListener('keydown', blockKeys);
}

function closeDocModal() {
  const modal = document.getElementById('docModal');
  const frame = document.getElementById('docFrame');

  frame.src = '';
  modal.classList.add('hidden');

  document.removeEventListener('contextmenu', blockContextMenu);
  document.removeEventListener('keydown', blockKeys);
}

function blockContextMenu(e) {
  e.preventDefault();
}

function blockKeys(e) {
  if (
    e.key === 'PrintScreen' ||
    (e.ctrlKey && ['p', 's', 'u'].includes(e.key.toLowerCase()))
  ) {
    e.preventDefault();
    alert('Screenshot & download dinonaktifkan');
  }
}
</script>





</html>
