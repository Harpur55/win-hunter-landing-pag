<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Win-Hunter.com</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/download.jpg') }} " class="rounded-full " /> @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans scroll-smooth">
    <section id="navbar" class="  bg-blue-800 shadow-md">
      <div class="flex items-center justify-between bg-blue-800 px-6 py-4">
        <!-- Logo dan Nama -->
        <div class="flex items-center">
          <img src="{{ asset('assets/images/download.jpg') }}" alt="Logo" class="h-16 w-16 rounded-full mr-4" />
          <span class="text-2xl font-sans font-bold text-white">Win-Hunter</span>
        </div>
        <!-- Hamburger Icon -->
        <div class="md:hidden">
          <button id="menu-toggle" class="text-white focus:outline-none text-3xl">&#9776;</button>
        </div>
        <div id="mobile-menu" class="fixed top-0 right-0 w-64 h-full bg-blue-700 transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden shadow-lg">
          <!-- Tombol Close (X) -->
          <div class="flex justify-end p-4">
            <button id="menu-close" aria-label="Close menu" class="text-white hover:text-gray-300 focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <!-- Menu Items -->
          <ul id="mobile-menu-list" class="flex flex-col px-6 text-white text-lg">
            <li class="border-b border-white py-3">
              <a href="#home" class="hover:text-gray-200">Home</a>
            </li>
            <li class="border-b border-white py-3">
              <a href="#about" class="hover:text-gray-200">tentang Kami</a>
            </li>
            <li class="border-b border-white py-3">
              <a href="#coach" class="hover:text-gray-200">Pelatih</a>
            </li>
            <li class="border-b border-white py-3">
              <a href="#jadwal" class="hover:text-gray-200">Jadwal Latihan</a>
            </li>
            <li class="border-b border-white py-3">
              <a href="#contact" class="hover:text-gray-200">Kontak & Alamat</a>
            </li>
          </ul>
        </div>
        <!-- Menu -->
        <div class="hidden md:block">
          <ul class="flex space-x-6 text-lg font-sans font-semibold">
            <li>
              <a href="#home" class="text-white hover:text-gray-200">Home</a>
            </li>
            <li>
              <a href="#content" class="text-white hover:text-gray-200">Tentang Kami</a>
            </li>
            <li>
              <a href="#coach" class="text-white hover:text-gray-200">Pelatih</a>
            </li>
            <li>
              <a href="#jadwal" class="text-white hover:text-gray-200">Jadwal Latihan</a>
            </li>
            <li>
              <a href="#contact" class="text-white hover:text-gray-200">Kontak & Alamat</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Mobile Menu --><div id="mobile-menu" class="fixed top-0 right-0 w-64 h-full bg-blue-700 transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden shadow-lg">
        <ul class="flex flex-col space-y-6 mt-20 px-6 text-white text-lg">
          <li>
            <a href="#home" class="hover:text-gray-200">Home</a>
          </li>
          <li>
            <a href="#content" class="hover:text-gray-200">About Us</a>
          </li>
          <li>
            <a href="#coach" class="hover:text-gray-200">Coach</a>
          </li>
          <li>
            <a href="#jadwal" class="hover:text-gray-200">Jadwal Latihan</a>
          </li>
          <li>
            <a href="#contact" class="hover:text-gray-200">Contact & Address</a>
          </li>
        </ul>
      </div>
    </section>
    <section class="bg-blue-700 py-10 px-6 md:px-20" id="home"><div class="container mx-auto flex flex-col-reverse md:flex-row items-center md:h-screen">
        <!-- TEXT AREA -->
        <div class="w-full md:w-1/2 text-center md:text-left mt-3 pt-3 md:pt-0">
          <h1 class="text-4xl md:text-6xl font-bold text-white mb-4"> Sacti Club <br> Win-Hunter </h1>
          <p class="text-2xl md:text-4xl text-white mb-6"> Mental, Instinct, Technique </p>
          <a href="#" class="inline-block bg-blue-600 text-white text-lg md:text-xl px-6 py-3 rounded-lg shadow hover:bg-orange-700 transition"> Join Sekarang! </a>
        </div>
        <!-- IMAGE AREA -->
        <div class="w-full md:w-1/2 flex justify-center items-center relative mb-4 md:mb-0 h-auto md:h-full mt-3">
          <img src="{{ asset('assets/images/new logo win-hunter.png') }}" alt="logo Win-Hunter" class="w-[200px] h-[200px] sm:w-[300px] sm:h-[300px] md:w-[430px] md:h-[500px] md:absolute md:top-1/2 md:left-1/2 md:transform md:-translate-x-1/2 md:-translate-y-1/2 object-cover" />
        </div>
      </div></section><section id="unit" class="bg-gray-300 py-8 px-2">
      <h1 class="text-4xl font-bold mb-6 text-center text-black">Unit</h1>
      <div class="overflow-hidden">
        <marquee behavior="scroll" direction="left" scrollamount="4">
          <div class="flex space-x-4"> @php $units = [ ['nama_unit' => 'Waterland Transyogi, Cileungsi', 'image' => 'download.jpg' ,'maps' =>'https://g.co/kgs/72XHDyL'], ['nama_unit' => 'SDIT Cahaya Sunah', 'image' => 'SDIT Cahaya Sunah.jpg','maps' => 'https://maps.app.goo.gl/q4tkBTQLsBQGqByR7'], ['nama_unit' => 'SMP Quran Asy Syahid', 'image' => 'ASYAHID.jpg'], ['nama_unit' => 'Al-Azhar Cibubur', 'image' => 'Al-Azhar.png'], ['nama_unit' => 'Al-Azhar Metland Cileungsi', 'image' => 'Al-Azhar.png'], ['nama_unit' => 'MI RUMAH ANAK', 'image' => 'MI RUMAH ANAK.png'], ['nama_unit' => 'Regina Caeli School,Cileungsi', 'image' => 'RC.jpg'] ]; @endphp @foreach($units as $unit) <div class="flex items-center bg-white border border-blue-800 rounded-lg shadow-lg hover:shadow-2xl w-[350px] min-w-[350px] p-4 ">
              <div class="flex-1 text-left">
                <h2 class="text-lg font-bold text-gray-800">{{ $unit['nama_unit'] }}</h2>
                <p class="text-sm text-gray-600 break-words whitespace-normal"></p>
              </div>
              <img src="{{ asset('assets/images/' . $unit['image']) }}" alt="{{ $unit['nama_unit'] }}" class="w-20 h-20 object-cover rounded-md ml-4 border border-gray-300" />
            </div> @endforeach </div>
        </marquee>
      </div>
    </section><section id="content" class="bg-gray-100 py-10 px-20">
      <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
          <div class="max-w-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Tentang Kami</h2>
            <p class="mt-4 text-gray-600 text-lg md:text-justify text-wrap"> Sacti Club Win-Hunter adalah klub taekwondo yang didirikan pada 15 Mei 2015 dan berpusat di lantai 2 kolam renang Metland Transyogi, Cileungsi. Klub ini dipimpin oleh Sabeumnim Samsul Arifin, pemegang sabuk hitam DAN IV Internasional dari Kukkiwon serta berlisensi sebagai wasit taekwondo nasional dan internasional. Dengan komitmen terhadap pembinaan mental, fisik, dan prestasi atlet, Sacti Club Win-Hunter menjadi tempat latihan taekwondo yang berfokus pada pengembangan karakter dan kompetensi dalam semangat sportivitas. </p>
          </div>
          <div class="mt-12 md:mt-0">
            <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86" alt="About Us Image" class="object-cover rounded-lg shadow-md">
          </div>
        </div>
      </div>
    </section>
    <section id="coach" class="py-10 px-4 sm:px-6 lg:px-2 bg-gray-300">
      <div class="container mx-auto flex flex-col items-center">
        <h2 class="text-3xl font-extrabold text-black sm:text-4xl mb-8">Pelatih</h2>
        <div class="swiper mySwiper ">
          <div class="swiper-wrapper"> @foreach($coaches as $coach) <div class="swiper-slide bg-white p-4 rounded-lg shadow-lg flex justify-center items-center">
              <div>
                {{-- <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"></a> --}}
                <img class="object-cover w-full rounded-t-lg h-50 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg " src="{{ asset($coach->foto) }}" alt="{{ $coach->coach_name }}">
                <div class="flex flex-col justify-between p-4 leading-normal">
                  <h5 class="mb-2 text-2xl font-bold tracking-tight text-black dark:text-black">{{ $coach->nama }}</h5>
                  <p class="mb-3 font-normal text-lg text-black dark:text-black">{{ $coach->Sabuk}}</p>
                  <p class="mb-3 font-normal text-lg text-black dark:text-black">{{ $coach->role }}</p>
                </div>
              </div>
            </div> @endforeach </div>
          <!-- Navigasi -->
          {{-- <div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div> --}}
          <div class="swiper-pagination mt-6"></div>
        </div>
      </div>
    </section>
    <section id="service" class="bg-gray-100 py-10 px-4 sm:px-6 lg:px-20">
      <h1 class="text-4xl font-extrabold mb-10 text-center text-gray-900">Kelas Taekwondo</h1> @php $class = [ [ 'name' => 'Kelas Prestasi', 'image' => 'cover1.jpeg', 'deskripsi' => "1. Latihan 4x Per Pekan\n" . "2. Program latihan tersusun dengan rapih (minimal kejuaraan 4x + ujian)\n" . "3. Apabila ada event tertentu mendapatkan diskon biaya\n" . "4. Sparing patner dengan club lain satu tahun minimal 2x\n", ], [ 'name' => 'Kelas Khusus', 'image' => 'cover1.jpeg', 'deskripsi' => "1. Latihan 2x Per Pekan\n" . "2. Sparing patner dengan club lain minimal 2x dalam setahun\n" . "3. Program latihan (wajib kejuaraan 2x dalam setahun)\n" . "4. Mengikuti Ujian Kenaikan Sabuk\n" , ], [ 'name' => 'Kelas Poomsae', 'image' => 'cover2.jpeg', 'deskripsi' => "1. Latihan 2x per Pekan\n" . "2. Program latihan tersusun dengan rapih\n" . "3. Latihan Rutin\n" . "4. Kejuaraan Wajib 2x dalam setahun\n", ], [ 'name' => 'Kelas Reguler', 'image' => 'download.jpg', 'deskripsi' => "1. Latihan seminggu 2x\n" . "2. Program latihan (ujian kenaikan tingkat dan wajib kejuaraan 2x dalam setahun)\n", ], ]; @endphp <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-4 gap-6"> @foreach($class as $cls) <div class="flex flex-col h-full bg-white border border-black rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
          <!-- Gambar -->
          <img src="{{ asset('assets/images/' . $cls['image']) }}" alt="{{ $cls['name'] }}" class="w-full h-50 object-cover">
          <!-- Konten -->
          <div class="flex flex-col flex-grow p-5 text-left">
            <!-- Judul -->
            <h3 class="text-xl font-semibold text-gray-800 mb-2">
              {{ $cls['name'] }}
            </h3>
            <!-- Deskripsi -->
            <div class="flex-grow">
              <p class="text-sm whitespace-pre-line leading-relaxed text-black text-justify">
                {{ $cls['deskripsi'] }}
              </p>
            </div>
            <!-- Tombol -->
            <div class="mt-2">
              <a href="#" class="inline-block w-full">
                <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300"> Daftar Sekarang </button>
              </a>
            </div>
          </div>
        </div> @endforeach </div>
      </div>
      {{-- @endforeach --}}
      </div>
      </div>
    </section><section id="jadwal" class="bg-gray-300 py-10 px-4 sm:px-6 lg:px-20">
      <div class="container mx-auto">
        <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Jadwal Latihan Pusat Dojang Waterland Metland Cileungsi</h2>
        <p class="text-md sm:text-xl text-gray-700 text-center mb-10"> Latihan rutin diadakan setiap hari dari Senin sampai Minggu. </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> @php $jadwal = [ ['hari' => 'Senin', 'kelas' => [['Kelas Prestasi', '16:00 - 18:45 WIB']]], ['hari' => 'Selasa', 'kelas' => [['Kelas Prestasi', '16:00 - 18:45 WIB']]], ['hari' => 'Rabu', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 18:45 WIB' ]]], ['hari'=> 'Kamis', 'kelas' => [['Kelas Reguler > 12 tahun', '16:00 - 18:45 WIB']]], ['hari' => 'Jumat', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 18:45 WIB' ]]], ['hari'=> 'Sabtu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '08:00 - 10:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ], ['hari' => 'Minggu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '07:30 - 09:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ] ]; @endphp @foreach ($jadwal as $j) <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 min-h-[200px] flex flex-col justify-between">
                <div>
                  <h3 class="text-3xl font-bold text-blue-700 mb-4">{{ $j['hari'] }}</h3> @foreach ($j['kelas'] as $kelas) <div class="mb-4">
                    <p class="text-gray-800 font-semibold text-3xl ">{{ $kelas[0] }}</p>
                    <p class="text-gray-600 text-2xl">{{ $kelas[1] }}</p>
                  </div> @endforeach
                </div>
              </div> @endforeach </div>
      </div>
    </section><section class="bg-white" id="contact">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
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
                <radialGradient id="TGwjmZMm2W~B4yrgup6jda_119026_gr1" cx="32" cy="32.5" r="31.259" gradientTransform="matrix(1 0 0 -1 0 64)" gradientUnits="userSpaceOnUse">
                  <stop offset="0" stop-color="#efdcb1"></stop>
                  <stop offset="0" stop-color="#f2e0bb"></stop>
                  <stop offset=".011" stop-color="#f2e0bc"></stop>
                  <stop offset=".362" stop-color="#f9edd2"></stop>
                  <stop offset=".699" stop-color="#fef4df"></stop>
                  <stop offset="1" stop-color="#fff7e4"></stop>
                </radialGradient>
                <path fill="url(#TGwjmZMm2W~B4yrgup6jda_119026_gr1)" d="M58,54c-1.1,0-2-0.9-2-2s0.9-2,2-2h2.5c1.9,0,3.5-1.6,3.5-3.5S62.4,43,60.5,43H50c-1.4,0-2.5-1.1-2.5-2.5	S48.6,38,50,38h8c1.7,0,3-1.3,3-3s-1.3-3-3-3H42v-6h18c2.3,0,4.2-2,4-4.4c-0.2-2.1-2.1-3.6-4.2-3.6H58c-1.1,0-2-0.9-2-2s0.9-2,2-2	h0.4c1.3,0,2.5-0.9,2.6-2.2c0.2-1.5-1-2.8-2.5-2.8h-14C43.7,9,43,8.3,43,7.5S43.7,6,44.5,6h3.9c1.3,0,2.5-0.9,2.6-2.2	C51.1,2.3,50,1,48.5,1H15.6c-1.3,0-2.5,0.9-2.6,2.2C12.9,4.7,14,6,15.5,6H19c1.1,0,2,0.9,2,2s-0.9,2-2,2H6.2c-2.1,0-4,1.5-4.2,3.6	C1.8,16,3.7,18,6,18h2.5c1.9,0,3.5,1.6,3.5,3.5S10.4,25,8.5,25H5.2c-2.1,0-4,1.5-4.2,3.6C0.8,31,2.7,33,5,33h17v11H6	c-1.7,0-3,1.3-3,3s1.3,3,3,3l0,0c1.1,0,2,0.9,2,2s-0.9,2-2,2H4.2c-2.1,0-4,1.5-4.2,3.6C-0.2,60,1.7,62,4,62h53.8	c2.1,0,4-1.5,4.2-3.6C62.2,56,60.3,54,58,54z"></path>
                <radialGradient id="TGwjmZMm2W~B4yrgup6jdb_119026_gr2" cx="18.51" cy="66.293" r="69.648" gradientTransform="matrix(.6435 -.7654 .5056 .4251 -26.92 52.282)" gradientUnits="userSpaceOnUse">
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
                <path fill="url(#TGwjmZMm2W~B4yrgup6jdb_119026_gr2)" d="M45,57H19c-5.5,0-10-4.5-10-10V21c0-5.5,4.5-10,10-10h26c5.5,0,10,4.5,10,10v26C55,52.5,50.5,57,45,57z"></path>
                <path fill="#fff" d="M32,20c4.6,0,5.1,0,6.9,0.1c1.7,0.1,2.6,0.4,3.2,0.6c0.8,0.3,1.4,0.7,2,1.3c0.6,0.6,1,1.2,1.3,2 c0.2,0.6,0.5,1.5,0.6,3.2C46,28.9,46,29.4,46,34s0,5.1-0.1,6.9c-0.1,1.7-0.4,2.6-0.6,3.2c-0.3,0.8-0.7,1.4-1.3,2 c-0.6,0.6-1.2,1-2,1.3c-0.6,0.2-1.5,0.5-3.2,0.6C37.1,48,36.6,48,32,48s-5.1,0-6.9-0.1c-1.7-0.1-2.6-0.4-3.2-0.6 c-0.8-0.3-1.4-0.7-2-1.3c-0.6-0.6-1-1.2-1.3-2c-0.2-0.6-0.5-1.5-0.6-3.2C18,39.1,18,38.6,18,34s0-5.1,0.1-6.9 c0.1-1.7,0.4-2.6,0.6-3.2c0.3-0.8,0.7-1.4,1.3-2c0.6-0.6,1.2-1,2-1.3c0.6-0.2,1.5-0.5,3.2-0.6C26.9,20,27.4,20,32,20 M32,17 c-4.6,0-5.2,0-7,0.1c-1.8,0.1-3,0.4-4.1,0.8c-1.1,0.4-2.1,1-3,2s-1.5,1.9-2,3c-0.4,1.1-0.7,2.3-0.8,4.1C15,28.8,15,29.4,15,34 s0,5.2,0.1,7c0.1,1.8,0.4,3,0.8,4.1c0.4,1.1,1,2.1,2,3c0.9,0.9,1.9,1.5,3,2c1.1,0.4,2.3,0.7,4.1,0.8c1.8,0.1,2.4,0.1,7,0.1 s5.2,0,7-0.1c1.8-0.1,3-0.4,4.1-0.8c1.1-0.4,2.1-1,3-2c0.9-0.9,1.5-1.9,2-3c0.4-1.1,0.7-2.3,0.8-4.1c0.1-1.8,0.1-2.4,0.1-7 s0-5.2-0.1-7c-0.1-1.8-0.4-3-0.8-4.1c-0.4-1.1-1-2.1-2-3s-1.9-1.5-3-2c-1.1-0.4-2.3-0.7-4.1-0.8C37.2,17,36.6,17,32,17L32,17z"></path>
                <path fill="#fff" d="M32,25c-5,0-9,4-9,9s4,9,9,9s9-4,9-9S37,25,32,25z M32,40c-3.3,0-6-2.7-6-6s2.7-6,6-6s6,2.7,6,6S35.3,40,32,40 z"></path>
                <circle cx="41" cy="25" r="2" fill="none"></circle>
              </svg>
            </span>
            <h2 class="mt-4 text-lg font-medium text-gray-800">Sosial Media</h2>
            <p class="mt-2 text-sm text-black">Follow Kami</p>
            <p class="mt-2 text-sm text-blue-500">
              <a href="https://www.instagram.com/sactiwinhunter?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">@sactiwinhunter
            </p>
            </a>
          </div>
          <!-- Phone -->
          <div class="text-center lg:text-left">
            <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
              <!-- Phone Icon -->
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 48 48">
                <path fill="#fff" d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"></path>
                <path fill="#fff" d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"></path>
                <path fill="#cfd8dc" d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"></path>
                <path fill="#40c351" d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"></path>
                <path fill="#fff" fill-rule="evenodd" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z" clip-rule="evenodd"></path>
              </svg>
            </span>
            <h2 class="mt-4 text-base font-medium text-gray-800">Phone</h2>
            <p class="mt-2 text-sm text-black">Kontak Kami</p>
            <a href="https://wa.me/6287772362124?text=Saya%20ingin%20Bertanya%20Tentang%20Taekwondo%20Win-Hunter
" target="_blank" class="inline-flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.52 3.48A11.85 11.85 0 0012.04 0C5.39 0 .04 5.35.04 11.96c0 2.1.55 4.17 1.6 5.99L0 24l6.25-1.63a12.03 12.03 0 005.79 1.47h.01c6.65 0 12.04-5.35 12.04-11.96a11.87 11.87 0 00-3.57-8.4zM12.04 22a9.92 9.92 0 01-5.06-1.37l-.36-.21-3.71.97.99-3.6-.24-.38a9.93 9.93 0 01-1.5-5.25c0-5.47 4.49-9.92 10.01-9.92 2.67 0 5.18 1.04 7.07 2.92a9.88 9.88 0 012.94 7.05c0 5.47-4.49 9.92-10.08 9.92zm5.5-7.57c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15s-.78.96-.95 1.16c-.17.2-.35.22-.65.07a8.09 8.09 0 01-2.39-1.48 9.01 9.01 0 01-1.66-2.06c-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.65-.93-2.26-.24-.58-.49-.5-.68-.51l-.58-.01c-.2 0-.52.07-.8.38-.28.3-1.06 1.03-1.06 2.5s1.09 2.9 1.24 3.1c.15.2 2.15 3.28 5.22 4.6.73.31 1.3.49 1.75.62.74.23 1.4.2 1.93.12.59-.09 1.77-.72 2.02-1.41.25-.7.25-1.3.17-1.42-.07-.12-.26-.19-.55-.33z" />
              </svg> Chat via WhatsApp </a>
          </div>
          <div class="text-center lg:text-left">
            <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
              <!-- Phone Icon -->
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 50 50">
                <path d="M41,4H9C6.243,4,4,6.243,4,9v32c0,2.757,2.243,5,5,5h32c2.757,0,5-2.243,5-5V9C46,6.243,43.757,4,41,4z M37.006,22.323 c-0.227,0.021-0.457,0.035-0.69,0.035c-2.623,0-4.928-1.349-6.269-3.388c0,5.349,0,11.435,0,11.537c0,4.709-3.818,8.527-8.527,8.527 s-8.527-3.818-8.527-8.527s3.818-8.527,8.527-8.527c0.178,0,0.352,0.016,0.527,0.027v4.202c-0.175-0.021-0.347-0.053-0.527-0.053 c-2.404,0-4.352,1.948-4.352,4.352s1.948,4.352,4.352,4.352s4.527-1.894,4.527-4.298c0-0.095,0.042-19.594,0.042-19.594h4.016 c0.378,3.591,3.277,6.425,6.901,6.685V22.323z"></path>
              </svg>
            </span>
            <h2 class="mt-4 text-base font-medium text-gray-800">Tik Tok</h2>
            <p class="mt-2 text-sm text-black">Follow kami juga di Tik tok</p>
            <p class="mt-2 text-sm text-blue-500">
              <a href="http://tiktok.com/@winhunterteam">@winhunterteam
            </p>
            </a>
          </div>
        </div>
        <!-- Maps Section -->
        <div class="mt-16">
          <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Maps Lokasi Latihan</h2>
          <div class="w-full flex justify-center">
            <iframe class="w-full lg:w-[75%] h-96 rounded-lg" frameborder="0" title="map" scrolling="no" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.9710922187955!2d106.97513277353566!3d-6.397726762570561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6995fdca64783f%3A0xfedb580d6d020f3a!2sWaterland%20Metland%20Transyogi!5e0!3m2!1sen!2sid!4v1749525799981!5m2!1sen!2sid" width="100" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </section><footer class="bg-blue-800 text-white py-6">
      <div class="container mx-auto text-center">
        <p class="text-lg">
          <img src="{{ asset('assets/images/download.jpg') }}" alt="" class="h-16 w-16 rounded-full inline-block mr-2"> Win-Hunter since 2015. Mental, Instinct, Technique
        </p>
      </div>
    </footer>
  </body><script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script><script>
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
</html>