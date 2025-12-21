@extends('ujian.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-4">Formulir Pendaftaran Ujian</h1>

    @include('ujian.layouts.partials.form', [
        'eventUjian' => $eventUjian,
        'siswas'     => $siswas,
        'units'      => $units,
        'kelas'      => $kelas,
        'sabukList'  => $sabukList,
        'mode'       => 'create',
        'pendaftaran' => null
    ])
</div>
@endsection
