<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sertifikat — Taekwondo (A4 Responsive)</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap');

    body {
      font-family: 'Inter', sans-serif;
    }

    /* A4 container */
    .a4 {
      width: 210mm;
      min-height: 297mm;
      background: #fff;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      border-radius: 6px;
      overflow: hidden;
    }

    /* Responsive centering */
    .a4-wrap {
      display: flex;
      justify-content: center;
      padding: 20px;
    }

    @media (max-width: 900px) {
      .a4 { transform: scale(0.7); transform-origin: top center; }
    }
    @media (max-width: 500px) {
      .a4 { transform: scale(0.55); }
    }

    
    .pattern {
      background-image: linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px),
                        linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px);
      background-size: 40px 40px;
      opacity: 0.9;
    }

    
    .gold-foil {
      background: linear-gradient(90deg, #f3d78a, #f2c85e 40%, #f1cf6e 60%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    /* Cap / stempel */
    .stamp {
      width: 110px;
      height: 110px;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(220,38,38,0.07);
      border: 3px solid rgba(220,38,38,0.45);
      transform: rotate(-6deg);
    }

    /* Gambar agar tidak besar */
    img.logo {
      max-width: 80px;
      height: auto;
    }

    /* Cetak */
    @page { size: A4; margin: 15mm; }
  </style>
</head>
<body class="bg-gray-100 font-sans">
  <div class="a4-wrap">
    <div class="a4 pattern p-10 text-gray-800">

      
      <div class="flex flex-col items-center text-center">
        <img src="C:\Users\HYPE\Documents\IMPORT WEB\win hunter.jpg" alt="Logo Taekwondo" class="logo mb-3">
        <h2 class="text-sm text-gray-600 tracking-widest">PENGURUS BESAR</h2>
        <h1 class="text-3xl md:text-4xl font-bold tracking-widest">TAEKWONDO INDONESIA</h1>
      </div>

    
      <div class="mt-8 text-center">
        <h1 class="font-playfair text-5xl md:text-6xl text-black font-bold">SERTIFIKAT</h1>
        <div class="mt-2 text-sm text-gray-600">
          Nomor Registrasi: <span class="font-semibold">2505080475257</span>
        </div>
      </div>

      
      <div class="mt-10 text-center">
        <p class="text-gray-700 text-sm md:text-base">Diberikan Kepada</p>
        <h2 class="text-3xl md:text-4xl font-bold mt-2">SURYA GHANI HODISAPUTRA</h2>
        <p class="mt-3 text-sm md:text-base text-gray-700">Tempat/Tgl Lahir: Cianjur, 09 November 2014</p>
        <p class="text-sm md:text-base text-gray-700">PENGPROV: DKI Jakarta</p>
        <p class="text-sm md:text-base text-gray-700 font-bold">GEUP : 9</p>

        <div class="mt-8 max-w-xl mx-auto text-sm md:text-base text-gray-700 leading-relaxed">
          Telah dinyatakan <span class="font-semibold">LULUS UJIAN KENAIKAN TINGKAT</span> yang diselenggarakan oleh
          <span class="font-semibold">Pengurus Besar Taekwondo Indonesia (P.B.T.I.)</span>.
        </div>

        <div class="mt-4 text-sm text-gray-700">
          Pada tanggal <span class="font-semibold">18 Mei 2025</span> di <span class="font-semibold">Jakarta Pusat</span>.
        </div>
      </div>

      
      <div class="mt-16 flex justify-between items-end px-6">
       
        <div class="text-left">
          <div class="text-sm text-gray-500">PENGUJI</div>
          <div class="mt-8 border-b border-gray-400 w-40"></div>
          <div class="mt-2 text-sm font-semibold">SYAMSUL ARIPIN</div>
          <div class="text-xs text-gray-500">DAN 4 / Kukkiwon</div>
        </div>

        
        <div class="text-right">
          <div class="text-sm text-gray-500">PENGPROV TI</div>
          <div class="mt-8 border-b border-gray-400 w-44 inline-block"></div>
          <div class="mt-2 text-sm font-semibold">MAYJEN TNI (PURN) IVAN R. PELEALU, SE., MM</div>
          <div class="text-xs text-gray-500">KETUA</div>
        </div>
      </div>

      
      <div class="mt-12 bg-gradient-to-r from-yellow-400 to-yellow-300 p-4 rounded-b-md text-xs text-gray-800">
        <div class="text-center mt-1">Dikeluarkan oleh: Sacti Club Win Hunter</div>
      </div>

    </div>
  </div>
</body>
</html>
