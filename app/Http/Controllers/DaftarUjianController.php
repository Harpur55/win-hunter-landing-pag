<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EventUjian;
use App\Models\Siswa;
use App\Models\Unit;
use App\Models\Kelas;

class DaftarUjianController extends Controller
{
    /**
     * Form daftar ujian
     */
    public function create($eventId)
    {
        $eventUjian = EventUjian::findOrFail($eventId);
        $units      = Unit::all();
        $kelas      = Kelas::all();
        $siswas     = Siswa::orderBy('nama_lengkap')->get();

        // ⬇️ Tambahkan sabukList
        $sabukList = [
            'putih' => 'Putih',
            'kuning' => 'Kuning',
            'kuning strip hijau' => 'Kuning Strip Hijau',
            'hijau' => 'Hijau',
            'hijau strip biru' => 'Hijau Strip Biru',
            'biru' => 'Biru',
            'biru strip merah' => 'Biru Strip Merah',
            'merah' => 'Merah',
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam' => 'Hitam',
        ];

        return view('ujian.daftar', [
            'eventUjian' => $eventUjian,
            'units'      => $units,
            'kelas'      => $kelas,
            'siswas'     => $siswas,
            'sabukList'  => $sabukList,   // ⬅️ ini yang tadi hilang
            'mode'       => 'create',
        ]);
    }

    /**
     * Simpan data pendaftaran ujian
     */
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'siswa_id'            => 'required|exists:siswas,id',
            'tempat_lahir'        => 'required',
            'tanggal_lahir'       => 'required|date',
            'no_register'         => ['required', 'regex:/^[0-9]{15}$/'],
            'current_belt_level'  => 'required',
            'next_belt_level'     => 'required',
            'units_id'            => 'required|exists:units,id',
            'kelas_id'            => 'required|exists:kelas,id',
        ]);

        DB::table('event_ujian_siswa')->insert([
            'event_ujian_id'     => $eventId,
            'siswa_id'           => $request->siswa_id,
            'tempat_lahir'       => $request->tempat_lahir,
            'tanggal_lahir'      => $request->tanggal_lahir,
            'no_register'        => $request->no_register,
            'current_belt_level' => $request->current_belt_level,
            'next_belt_level'    => $request->next_belt_level,
            'units_id'           => $request->units_id,
            'kelas_id'           => $request->kelas_id,
            'keterangan'         => 'on_proses',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return back()->with('success', 'Data berhasil didaftarkan! Status ujian: On Proses');
    }
}
