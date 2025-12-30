@extends('ujian.layouts.app')

@section('title', 'Ujian Telah Berakhir')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8 text-center">

        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="bg-red-100 text-red-600 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-10 w-10"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                </svg>
            </div>
        </div>

        {{-- Judul --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Ujian Telah Berakhir
        </h1>

        {{-- Deskripsi --}}
        <p class="text-gray-600 mb-4">
            Ujian
            <span class="font-semibold">
                {{ $eventUjian->nama_ujian ?? 'ini' }}
            </span>
            sudah tidak dapat diakses.
        </p>

        {{-- Info tanggal --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm text-gray-700">
            <p>
                <span class="font-medium">Tanggal Ujian:</span>
                {{ \Carbon\Carbon::parse($eventUjian->tanggal_ujian)->translatedFormat('d F Y') }}
            </p>
            <p class="mt-1 text-red-600 font-medium">
                Pendaftaran ditutup pada hari ujian.
            </p>
        </div>

        {{-- Catatan --}}
        <p class="text-gray-500 text-sm mb-6">
            Silakan hubungi panitia atau admin unit jika membutuhkan informasi lebih lanjut.
        </p>

        {{-- Tombol --}}
        <div class="flex justify-center">
            <a href="{{ url('/') }}"
               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection
