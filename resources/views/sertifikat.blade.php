<!doctype html>
<html lang="id">

<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Taekwondo Indonesia</title>

    <script src="/_sdk/element_sdk.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&amp;family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">

    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        html,
        body,
        .app-wrapper {
            height: 100%;
            width: 100%;
        }

        .app-wrapper {
            overflow-y: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .certificate-container {
            width: 210mm;
            height: 297mm;
            min-width: 210mm;
            min-height: 297mm;
            position: relative;
            overflow: hidden;
        }

        @media screen and (max-width: 250mm) {
            .certificate-container {
                transform: scale(0.9);
                transform-origin: top center;
            }
        }

        @media screen and (max-width: 220mm) {
            .certificate-container {
                transform: scale(0.7);
                transform-origin: top center;
            }
        }

        @media screen and (max-width: 180mm) {
            .certificate-container {
                transform: scale(0.5);
                transform-origin: top center;
            }
        }

        /* Gold foil effect */
        .gold-foil {
            background: linear-gradient(135deg, #D4AF37 0%, #F4E5B0 25%, #D4AF37 50%, #B8860B 75%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Decorative pattern background */
        .pattern-bg {
            background-image:
                radial-gradient(circle at 20% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, #fefefe 0%, #f9f9f9 100%);
        }

        /* Ornamental border */
        .ornate-border {
            border: 3px solid #D4AF37;
            box-shadow:
                inset 0 0 0 8px #fff,
                inset 0 0 0 10px #D4AF37,
                inset 0 0 0 18px #fff,
                inset 0 0 0 20px rgba(212, 175, 55, 0.3),
                0 10px 40px rgba(0, 0, 0, 0.15);
        }

        /* Corner decorations */
        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            opacity: 0.6;
        }

        .corner-top-left {
            top: 25px;
            left: 25px;
            border-top: 3px solid #D4AF37;
            border-left: 3px solid #D4AF37;
        }

        .corner-top-right {
            top: 25px;
            right: 25px;
            border-top: 3px solid #D4AF37;
            border-right: 3px solid #D4AF37;
        }

        .corner-bottom-left {
            bottom: 25px;
            left: 25px;
            border-bottom: 3px solid #D4AF37;
            border-left: 3px solid #D4AF37;
        }

        .corner-bottom-right {
            bottom: 25px;
            right: 25px;
            border-bottom: 3px solid #D4AF37;
            border-right: 3px solid #D4AF37;
        }

        /* Logo placeholder */
      

        /* Signature line */
        .signature-line {
            width: 180px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #666, transparent);
            margin: 8px 0;
        }

        /* Decorative divider */
        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 20px 0;
        }

        .divider-line {
            width: 100px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
        }

        .divider-diamond {
            width: 8px;
            height: 8px;
            background: #D4AF37;
            transform: rotate(45deg);
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

            .app-wrapper {
                padding: 0;
                background: white !important;
            }

            .certificate-container {
                box-shadow: none;
                transform: none !important;
                width: 210mm;
                height: 297mm;
            }
        }
           @view-transition {
            navigation: auto;
        }
        .background-image img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: auto;
            opacity: 0.1;
            pointer-events: none;
           
        }
    </style
  
    
  
</head>

<body>
    <div class="app-wrapper" id="app-bg">
        <div class="certificate-container ornate-border pattern-bg rounded-lg" id="certificate">
            <!-- Corner Decorations -->
            <div class="corner-decoration corner-top-left"></div>
            <div class="corner-decoration corner-top-right"></div>
            <div class="corner-decoration corner-bottom-left"></div>
            <div class="corner-decoration corner-bottom-right"></div>
            <div class="p-8 md:p-12 h-full flex flex-col"><!-- Header -->
                <div class="flex flex-col items-center text-center">
                    <p class="text-xm tracking-[0.3em] text-gray-500 font-medium mt-2">PENGURUS BESAR</p>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-[0.15em] gold-foil mt-1" id="org-title">TAEKWONDO
                        INDONESIA</h1>
                </div>
                <div class="divider">
                  <div class="background-image">
                <img src="{{ asset('assets/images/download.jpg') }}" alt="Gambar" class="w-40 rounded-lg">
                  
                    </div>
                </div><!-- Certificate Title -->
                <div class="text-center">
                    <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-800 tracking-wide"
                        id="cert-title">SERTIFIKAT</h2>
                    <p class="text-xs text-gray-500 mt-2 tracking-wider">KENAIKAN TINGKAT</p>
                </div>
                <div class="no_register text-center">
                    <p class="text-xl text-black mt-2 tracking-wider">No. Sertifikat: 001/SCWH/VI/2025</p>
                    <p class="text-xl text-black mt-2 tracking-wider">No. Register Siswa: 123456789012345</p>

                  </div>
                <div class="flex-1 flex flex-col justify-center text-center mt-2">
                    <p class="text-sm text-gray-600 italic">Diberikan kepada</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mt-3 tracking-wide" id="nama-penerima">SURYA
                        GHANI HODISAPUTRA</h3>
                    <div class="mt-6 space-y-1">
                        <p class="text-sm text-gray-600">Tempat/Tgl Lahir: <span class="font-semibold text-gray-800"
                                id="ttl">Cianjur, 09 November 2014</span></p>
                        <p class="text-sm text-gray-600">Sabuk: 
                          <span class="font-bold text-lg"
                                id="sabuk">KUNING</span>
                                </p>
                    </div>
                    <div class="mt-6 max-w-lg mx-auto">
                        <p class="text-sm text-gray-700 leading-relaxed">Telah dinyatakan <span
                                class="font-semibold">LULUS UJIAN KENAIKAN TINGKAT</span> yang diselenggarakan oleh
                            <span class="font-semibold">Pengurus Besar Taekwondo Indonesia (P.B.T.I.)</span></p>
                        <p class="text-sm text-gray-700 mt-3">Pada tanggal <span class="font-semibold"
                                id="tanggal-ujian">18 Mei 2025</span> di <span class="font-semibold"
                                id="tempat-ujian">Jakarta Pusat</span></p>
                    </div>
                </div><!-- Signatures -->
                <div class="mt-auto">
                    <div class="flex justify-between items-end px-4 md:px-8"><!-- Left Signature -->
                        <div class="flex-shrink-0 mx-4">
                
                        </div><!-- Right Signature -->
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-10">PEMBINA CLUB</p>
                            <div class="signature-line mx-auto"></div>
                            <p class="text-sm font-semibold text-gray-800 mt-1 max-w-[200px]" id="nama-ketua">SYAMSUL ARIPIN</p>
                            <p class="text-xs text-gray-500">KETUA</p>
                        </div>
                    </div><!-- Footer -->
                    <div
                        class="mt-6 bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-400 py-3 px-4 rounded-b-lg -mx-8 md:-mx-12 -mb-8 md:-mb-12">
                        <p class="text-center text-xs font-medium text-gray-800">Dikeluarkan oleh: <span
                                class="font-bold">Sacti Club Win Hunter</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const defaultConfig = {
            nama_penerima: "SURYA GHANI HODISAPUTRA",
            tempat_lahir: "Cianjur",
            tanggal_lahir: "09 November 2014",
            pengprov: "DKI Jakarta",
            geup: "9",
            tanggal_ujian: "18 Mei 2025",
            tempat_ujian: "Jakarta Pusat",
            nama_penguji: "SYAMSUL ARIPIN",
            dan_penguji: "DAN 4 / Kukkiwon",
            nama_ketua: "MAYJEN TNI (PURN) IVAN R. PELEALU, SE., MM",
            background_color: "#f3f4f6",
            primary_color: "#D4AF37",
            text_color: "#1f2937",
            surface_color: "#ffffff",
            accent_color: "#fbbf24",
            font_family: "Inter",
            font_size: 16
        };

        async function onConfigChange(config) {
            // Update text content
            document.getElementById('nama-penerima').textContent = config.nama_penerima || defaultConfig.nama_penerima;
            document.getElementById('ttl').textContent =
                `${config.tempat_lahir || defaultConfig.tempat_lahir}, ${config.tanggal_lahir || defaultConfig.tanggal_lahir}`;
            document.getElementById('pengprov').textContent = config.pengprov || defaultConfig.pengprov;
            document.getElementById('geup').textContent = config.geup || defaultConfig.geup;
            document.getElementById('tanggal-ujian').textContent = config.tanggal_ujian || defaultConfig.tanggal_ujian;
            document.getElementById('tempat-ujian').textContent = config.tempat_ujian || defaultConfig.tempat_ujian;
            document.getElementById('nama-penguji').textContent = config.nama_penguji || defaultConfig.nama_penguji;
            document.getElementById('dan-penguji').textContent = config.dan_penguji || defaultConfig.dan_penguji;
            document.getElementById('nama-ketua').textContent = config.nama_ketua || defaultConfig.nama_ketua;

            // Update colors
            const bgColor = config.background_color || defaultConfig.background_color;
            const primaryColor = config.primary_color || defaultConfig.primary_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const surfaceColor = config.surface_color || defaultConfig.surface_color;
            const accentColor = config.accent_color || defaultConfig.accent_color;

            document.getElementById('app-bg').style.backgroundColor = bgColor;

            // Update certificate styling
            const cert = document.getElementById('certificate');
            cert.style.backgroundColor = surfaceColor;
            cert.style.borderColor = primaryColor;
            cert.style.boxShadow =
                `inset 0 0 0 8px ${surfaceColor}, inset 0 0 0 10px ${primaryColor}, inset 0 0 0 18px ${surfaceColor}, inset 0 0 0 20px ${primaryColor}33, 0 10px 40px rgba(0,0,0,0.15)`;

            // Update corner decorations
            document.querySelectorAll('.corner-decoration').forEach(corner => {
                corner.style.borderColor = primaryColor;
            });

            // Update gold foil elements
            document.querySelectorAll('.gold-foil').forEach(el => {
                el.style.background =
                    `linear-gradient(135deg, ${primaryColor} 0%, ${accentColor} 25%, ${primaryColor} 50%, ${primaryColor} 75%, ${primaryColor} 100%)`;
                el.style.webkitBackgroundClip = 'text';
                el.style.webkitTextFillColor = 'transparent';
                el.style.backgroundClip = 'text';
            });

            // Update text colors
            document.querySelectorAll('.text-gray-800, .text-gray-900').forEach(el => {
                el.style.color = textColor;
            });

            // Update fonts
            const fontFamily = config.font_family || defaultConfig.font_family;
            const fontSize = config.font_size || defaultConfig.font_size;
            const baseFontStack = 'Inter, sans-serif';

            document.body.style.fontFamily = `${fontFamily}, ${baseFontStack}`;
            document.getElementById('nama-penerima').style.fontSize = `${fontSize * 1.75}px`;
        }

        function mapToCapabilities(config) {
            return {
                recolorables: [{
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            window.elementSdk.setConfig({
                                background_color: value
                            });
                        }
                    },
                    {
                        get: () => config.surface_color || defaultConfig.surface_color,
                        set: (value) => {
                            config.surface_color = value;
                            window.elementSdk.setConfig({
                                surface_color: value
                            });
                        }
                    },
                    {
                        get: () => config.text_color || defaultConfig.text_color,
                        set: (value) => {
                            config.text_color = value;
                            window.elementSdk.setConfig({
                                text_color: value
                            });
                        }
                    },
                    {
                        get: () => config.primary_color || defaultConfig.primary_color,
                        set: (value) => {
                            config.primary_color = value;
                            window.elementSdk.setConfig({
                                primary_color: value
                            });
                        }
                    },
                    {
                        get: () => config.accent_color || defaultConfig.accent_color,
                        set: (value) => {
                            config.accent_color = value;
                            window.elementSdk.setConfig({
                                accent_color: value
                            });
                        }
                    }
                ],
                borderables: [],
                fontEditable: {
                    get: () => config.font_family || defaultConfig.font_family,
                    set: (value) => {
                        config.font_family = value;
                        window.elementSdk.setConfig({
                            font_family: value
                        });
                    }
                },
                fontSizeable: {
                    get: () => config.font_size || defaultConfig.font_size,
                    set: (value) => {
                        config.font_size = value;
                        window.elementSdk.setConfig({
                            font_size: value
                        });
                    }
                }
            };
        }

        function mapToEditPanelValues(config) {
            return new Map([
                ["nama_penerima", config.nama_penerima || defaultConfig.nama_penerima],
                ["tempat_lahir", config.tempat_lahir || defaultConfig.tempat_lahir],
                ["tanggal_lahir", config.tanggal_lahir || defaultConfig.tanggal_lahir],
                ["pengprov", config.pengprov || defaultConfig.pengprov],
                ["geup", config.geup || defaultConfig.geup],
                ["tanggal_ujian", config.tanggal_ujian || defaultConfig.tanggal_ujian],
                ["tempat_ujian", config.tempat_ujian || defaultConfig.tempat_ujian],
                ["nama_penguji", config.nama_penguji || defaultConfig.nama_penguji],
                ["dan_penguji", config.dan_penguji || defaultConfig.dan_penguji],
                ["nama_ketua", config.nama_ketua || defaultConfig.nama_ketua]
            ]);
        }

        // Initialize SDK
        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities,
                mapToEditPanelValues
            });
        } else {
            // Fallback for preview
            onConfigChange(defaultConfig);
        }
    </script>
    <script>
        (function() {
            function c() {
                var b = a.contentDocument || a.contentWindow.document;
                if (b) {
                    var d = b.createElement('script');
                    d.innerHTML =
                        "window.__CF$cv$params={r:'9b281fef834734b6',t:'MTc2NjQ5NTY5NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
                    b.getElementsByTagName('head')[0].appendChild(d)
                }
            }
            if (document.body) {
                var a = document.createElement('iframe');
                a.height = 1;
                a.width = 1;
                a.style.position = 'absolute';
                a.style.top = 0;
                a.style.left = 0;
                a.style.border = 'none';
                a.style.visibility = 'hidden';
                document.body.appendChild(a);
                if ('loading' !== document.readyState) c();
                else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
                else {
                    var e = document.onreadystatechange || function() {};
                    document.onreadystatechange = function(b) {
                        e(b);
                        'loading' !== document.readyState && (document.onreadystatechange = e, c())
                    }
                }
            }
        })();
    </script>
     <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
</body>

</html>
