<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<title>Win-Hunter.com</title>

 @vite(['resources/css/app.css', 'resources/js/app.js'])



</head>
<body class="font-sans">
  

   <section id="navbar" class="bg-blue-500">
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

    <div id="mobile-menu"
  class="fixed top-0 right-0 w-64 h-full bg-blue-700 transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden shadow-lg">
  
  <!-- Tombol Close (X) -->
  <div class="flex justify-end p-4">
    <button id="menu-close" aria-label="Close menu" class="text-white hover:text-gray-300 focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>

  <!-- Menu Items -->
  <ul id="mobile-menu-list" class="flex flex-col px-6 text-white text-lg">
    <li class="border-b border-white py-3"><a href="#" class="hover:text-gray-200">Home</a></li>
    <li class="border-b border-white py-3"><a href="#" class="hover:text-gray-200">About Us</a></li>
    <li class="border-b border-white py-3"><a href="#" class="hover:text-gray-200">Coach</a></li>
    <li class="border-b border-white py-3"><a href="#" class="hover:text-gray-200">Jadwal Latihan</a></li>
    <li class="border-b border-white py-3"><a href="#" class="hover:text-gray-200">Contact & Address</a></li>
  </ul>
</div>

    <!-- Menu -->
     <div class="hidden md:block">
      <ul class="flex space-x-6 text-lg font-sans font-semibold">
        <li><a href="#" class="text-white hover:text-gray-200">Home</a></li>
        <li><a href="#" class="text-white hover:text-gray-200">About Us</a></li>
        <li><a href="#" class="text-white hover:text-gray-200">Coach</a></li>
        <li><a href="#" class="text-white hover:text-gray-200">Jadwal Latihan</a></li>
        <li><a href="#" class="text-white hover:text-gray-200">Contact & Address</a></li>
      </ul>
    </div> 
  </div>

  <!-- Mobile Menu -->
<div id="mobile-menu"
    class="fixed top-0 right-0 w-64 h-full bg-blue-700 transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden shadow-lg">
    <ul class="flex flex-col space-y-6 mt-20 px-6 text-white text-lg">
      <li><a href="#" class="hover:text-gray-200">Home</a></li>
      <li><a href="#" class="hover:text-gray-200">About Us</a></li>
      <li><a href="#" class="hover:text-gray-200">Coach</a></li>
      <li><a href="#" class="hover:text-gray-200">Jadwal Latihan</a></li>
      <li><a href="#" class="hover:text-gray-200">Contact & Address</a></li>
    </ul>
  </div>
</section>


   <section id="hero" class="bg-blue-700 py-10 px-6 md:px-20">
  <div class="container mx-auto flex flex-col-reverse md:flex-row items-center">
    
    <!-- TEKS (Kiri di desktop, bawah di mobile) -->
    <div class="w-full md:w-1/2 text-center md:text-left mt-6 md:mt-7 pt-6 md:pt-5">
      <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 animate__animated animate__lightSpeedInLeft">
        Sacti Club <br> Win-Hunter
      </h1>
      <p class="text-2xl md:text-4xl text-white mb-6 animate__animated animate__fadeInDownBig">
        Mental, Instinc, Technique
      </p>
      <a href="#" class="inline-block bg-blue-600 text-white text-lg md:text-xl px-6 py-3 rounded-lg shadow hover:bg-orange-700 transition">
       Join Sekarang!
      </a>
    </div>

    <!-- GAMBAR + SVG BACKGROUND -->
    <div class="w-full md:w-1/2 relative flex justify-center items-center mb-6 md:mb-2 sm:ml-52">
      <!-- SVG background -->
      <svg viewBox="-120 -120 240 240" xmlns="http://www.w3.org/2000/svg" class="w-[450px] md:w-[450px] h-auto">
        <path d="M 79.03496267746436,0 C 79.0078420945678,2.4730963843328544 -34.09302575936141,64.21482637442718 -36.374086153075645,63.00176529601462 C -38.65514654678988,61.78870421760205 -37.32623798485836,-59.39301861470781 -35.01805700824756,-60.6530539206281 C -32.70987603163676,-61.913089226548394 79.06208326036092,-2.4730963843328544 79.03496267746436,0 Z" fill="#E84A5F" />
      </svg>

      <!-- Image overlay -->
      <img src="{{ asset('assets/images/sanim.png') }}" alt="Sanim"
           class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
                  w-[500px] h-[500px] md:w-[800px] md:h-[500px] object-cover mb-2 items-center " />
    </div>

  </div>
</section>


 <section id="unit" class="bg-gray-300 py-8 px-4">
  <h1 class="text-4xl font-bold mb-6 text-center text-black">Unit</h1>

  <div class="overflow-hidden">
    <marquee behavior="scroll" direction="left" scrollamount="4">
      <div class="flex space-x-4">
        @php
          $units = [
            ['name' => 'SDIT Cahaya Sunah', 'image' => 'SDIT Cahaya Sunah.jpg'],
            ['name' => 'ASYAHID', 'image' => 'ASYAHID.jpg'],
            ['name' => 'Al-Azhar', 'image' => 'Al-Azhar.png'],
            ['name' => 'MI RUMAH ANAK', 'image' => 'MI RUMAH ANAK.png'],
            ['name' => 'RC', 'image' => 'RC.jpg'],
            ['name' => 'Waterland Metland', 'image' => 'download.jpg'],
          ];
        @endphp

        @foreach($units as $unit)
        <div class="flex items-center bg-white border border-blue-800 rounded-lg shadow-lg hover:shadow-2xl w-[300px] min-w-[300px] p-4 ">
          <div class="flex-1 text-left">
            <h2 class="text-lg font-bold text-gray-800">{{ $unit['name'] }}</h2>
            <p class="text-sm text-gray-600">Informasi singkat unit.</p>
          </div>
          <img src="{{ asset('assets/images/' . $unit['image']) }}" alt="{{ $unit['name'] }}"
               class="w-20 h-20 object-cover rounded-md ml-4 border border-gray-300" />
        </div>
        @endforeach
      </div>
    </marquee>
  </div>
</section>




    <section id="content" class="bg-gray-100 py-10 px-20">
       <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
            <div class="max-w-lg">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">About Us</h2>
                <p class="mt-4 text-gray-600 text-lg md:text-justify text-wrap">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis
                    eros at lacus feugiat hendrerit sed ut tortor. Suspendisse et magna quis elit efficitur consequat.
                    Mauris eleifend velit a pretium iaculis. Donec sagittis velit et magna euismod, vel aliquet nulla
                    malesuada. Nunc pharetra massa lectus, a fermentum arcu volutpat vel.</p>
                <div class="mt-8">
                    <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Learn more about us
                        <span class="ml-2">&#8594;</span></a>
                </div>
            </div>
            <div class="mt-12 md:mt-0">
                <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86" alt="About Us Image" class="object-cover rounded-lg shadow-md">
            </div>
        </div>
    </div>
    </section>

    
    <section id="coach" class=" py-10 px-4 sm:px-6 lg:px-2 bg-gray-300">
  <div class="container mx-auto flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black sm:text-4xl mb-8"> Our Coaches</h2>

    <div class="swiper mySwiper ">
      <div class="swiper-wrapper">

        @php
            $coaches = [
            // ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam DAN IV'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam'],
            ['name' => 'Sabeumnim Syamsul Aripin', 'image' => 'sanim.png', 'Sabuk' => 'Sabuk Hitam'],
           
            ]
        @endphp
        @foreach($coaches as $coach)
        <div class="swiper-slide bg-white p-4 rounded-lg shadow-lg flex justify-center items-center">
          <div>
        {{-- <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"></a> --}}
        <img class="object-cover w-full rounded-t-lg h-50 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="{{ asset('assets/images/' . $unit['image']) }}" alt="">
       <div class="flex flex-col justify-between p-4 leading-normal">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-black dark:text-black">{{ $unit['name'] }}</h5>
        <p class="mb-3 font-normal text-lg text-black dark:text-gray-400">{{ $coach['Sabuk']}}</p>
       </div>
    </div>


        </div>
        @endforeach
      </div>

      <!-- Navigasi -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-pagination mt-4"></div>
    </div>
  </div>
</section>
<section id="service" class="bg-gray-100 py-10 px-4 sm:px-6 lg:px-20">
  <h1 class="text-4xl font-extrabold mb-10 text-center text-gray-900">Our Class</h1>

  @php
  $class = [
      ['name' => 'Kelas Prestasi', 'image' => 'download.jpg' , 'deskripsi'=>'deskripsi'],
      ['name' => 'Kelas Reguler', 'image' => 'download.jpg','deskripsi'=>'deskripsi'],
      ['name' => 'Kelas Khusus', 'image' => 'download.jpg','deskripsi'=>'deskripsi'],
      ['name' => 'Kelas Poomsae', 'image' => 'download.jpg','deskripsi'=>'deskripsi']
  ];
  @endphp

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($class as $cls)
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <img
          src="{{ asset('assets/images/' . $cls['image']) }}"
          alt="{{ $cls['name'] }}"
          class="w-48 h-48 object-cover items-center mx-auto"
        >
        <div class="p-5">
          <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $cls['name'] }}</h3>
          <p class="text-gray-600 text-sm mb-4">
            {{ $cls['deskripsi'] }}
          </p>
          <a href="#"
             class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            Read more
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </a>
        </div>
      </div>
    @endforeach
  </div>
</section>


<<section id="jadwal" class="bg-gray-300 py-10 px-4 sm:px-6 lg:px-20">
  <div class="container mx-auto">
    <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Jadwal Latihan Pusat Dojang Waterland Metland Cileungsi</h2>
    <p class="text-md sm:text-xl text-gray-700 text-center mb-10">
      Latihan rutin diadakan setiap hari dari Senin sampai Minggu.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @php
      $jadwal = [
        ['hari' => 'Senin', 'kelas' => [['Kelas Prestasi', '16:00 - 18:45 WIB']]],
        ['hari' => 'Selasa', 'kelas' => [['Kelas Prestasi', '16:00 - 18:45 WIB']]],
        ['hari' => 'Rabu', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 18:45 WIB']]],
        ['hari' => 'Kamis', 'kelas' => [['Kelas Reguler > 12 tahun', '16:00 - 18:45 WIB']]],
        ['hari' => 'Jumat', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 18:45 WIB']]],
        ['hari' => 'Sabtu', 'kelas' => [
            ['Kelas Reguler Semua Sabuk', '08:00 - 10:45 WIB'],
            ['Kelas Poomsae', '10:00 - 12:45 WIB']
          ]
        ],
        ['hari' => 'Minggu', 'kelas' => [
            ['Kelas Reguler Semua Sabuk', '07:30 - 09:45 WIB'],
            ['Kelas Poomsae', '10:00 - 12:45 WIB']
          ]
        ]
      ];
      @endphp

      @foreach ($jadwal as $j)
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 min-h-[200px] flex flex-col justify-between">
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

<<section class="bg-white">
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
        <h2 class="mt-4 text-base font-medium text-gray-800">Email</h2>
        <p class="mt-2 text-sm text-gray-500">Our friendly team is here to help.</p>
        <p class="mt-2 text-sm text-blue-500">hello@merakiui.com</p>
      </div>

      <!-- Office -->
      <div class="text-center lg:text-left">
        <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
          <!-- Office Icon -->
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
          </svg>
        </span>
        <h2 class="mt-4 text-base font-medium text-gray-800">Office</h2>
        <p class="mt-2 text-sm text-gray-500">Come say hello at our office HQ.</p>
        <p class="mt-2 text-sm text-blue-500">100 Smith Street Collingwood VIC 3066 AU</p>
      </div>

      <!-- Phone -->
      <div class="text-center lg:text-left">
        <span class="inline-block p-3 text-blue-500 rounded-full bg-blue-100/80">
          <!-- Phone Icon -->
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
          </svg>
        </span>
        <h2 class="mt-4 text-base font-medium text-gray-800">Phone</h2>
        <p class="mt-2 text-sm text-gray-500">Mon-Fri from 8am to 5pm.</p>
        <p class="mt-2 text-sm text-blue-500">+1 (555) 000-0000</p>
      </div>
    </div>

    <!-- Maps Section -->
    <div class="mt-16">
      <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Maps Lokasi Latihan</h2>
      <div class="w-full flex justify-center">
        <iframe
          class="w-full lg:w-[75%] h-96 rounded-lg"
          frameborder="0"
          title="map"
          scrolling="no"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.9710922187955!2d106.97513277353566!3d-6.397726762570561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6995fdca64783f%3A0xfedb580d6d020f3a!2sWaterland%20Metland%20Transyogi!5e0!3m2!1sen!2sid!4v1749525799981!5m2!1sen!2sid" width="100" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</section>



    <footer class="bg-blue-800 text-white py-6">
      <div class="container mx-auto text-center">
        <p><img src="{{ asset('assets/images/download.jpg') }}" alt="" class="h-16 w-16 rounded-full inline-block mr-2"> 2023 Win-Hunter. All rights reserved.</p></p>
         
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
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
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