<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} | {{ $title }}</title>
    @vite('resources/css/app.css')
    <style>
        body {
            margin: 0;
            background: url('https://4kwallpapers.com/images/walls/thumbs_3t/21278.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            position: relative;
        }

        /* Overlay gelap biar teks terbaca */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.55); /* hitam transparan */
            z-index: 0;
        }

        /* Animasi slow zoom di background */
        body::after {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: inherit;
            background-size: cover;
            background-position: center;
            transform: scale(1);
            animation: zoom 20s infinite alternate ease-in-out;
            z-index: -1;
        }

        @keyframes zoom {
            from { transform: scale(1); }
            to   { transform: scale(1.1); }
        }

        .container {
            position: relative;
            z-index: 1; /* di atas overlay */
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans">

    <div class="container">
        <div class="icon">
            <img src="" alt="">
        </div>

        <h1 class="text-6xl mb-4">{{ $icon }}</h1>
        <h2 class="text-3xl font-extrabold mb-2">{{ $title }}</h2>
        <p class="text-lg mb-6">{{ $message }}</p>
        <a href="{{ url('/') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
           Reload
        </a>
    </div>

</body>
</html>
