@extends('ujian.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto my-4 bg-white rounded-3xl shadow-xl overflow-hidden">

    <!-- HEADER CARD -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 p-6 sm:p-8 text-white">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5">

            <!-- LOGO -->
            <div class="flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-full 
                        bg-white shadow-md ring-4 ring-white/30 overflow-hidden">
                <img
                    src="https://www.win-hunter.com/assets/images/download.jpg"
                    alt="Logo Club"
                    class="h-full w-full object-cover rounded-full"
                    onerror="this.style.display='none'"
                >
            </div>

            <!-- TEXT -->
            <div class="text-center sm:text-left">
                <p class="text-xs sm:text-sm uppercase tracking-widest text-blue-200">
                    Sacti Win-Hunter
                </p>

                <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold leading-tight">
                    Formulir Pendaftaran Ujian
                </h1>

                <p class="mt-1 text-sm sm:text-lg font-semibold text-blue-100">
                    {{ $eventUjian->nama_ujian ?? 'Ujian Kenaikan Tingkat' }}
                </p>
            </div>
        </div>

        <!-- DIVIDER -->
        <div class="mt-6 h-px w-full bg-white/20"></div>

        <p class="mt-3 text-xs sm:text-sm text-blue-100 text-center sm:text-left">
            Lengkapi data peserta dengan benar sebelum melakukan pendaftaran
        </p>
    </div>

    <!-- BODY CARD -->
    <div class="p-6 sm:p-8">

        @include('ujian.layouts.partials.form', [
            'eventUjian'  => $eventUjian,
            'siswas'      => $siswas,
            'units'       => $units,
            'kelas'       => $kelas,
            'sabukList'   => $sabukList,
            'mode'        => 'create',
            'pendaftaran' => null
        ])

    </div>
</div>

