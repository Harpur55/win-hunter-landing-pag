<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $units      = Unit::orderBy('name')->get();
        $kelas      = Kelas::orderBy('name')->get();
        $siswas     = Siswa::orderBy('nama_lengkap')->get();

        $sabukList = [
            'putih'                 => 'Putih',
            'kuning'                => 'Kuning',
            'kuning strip hijau'    => 'Kuning Strip Hijau',
            'hijau'                 => 'Hijau',
            'hijau strip biru'      => 'Hijau Strip Biru',
            'biru'                  => 'Biru',
            'biru strip merah'      => 'Biru Strip Merah',
            'merah'                 => 'Merah',
            'merah strip hitam 1'   => 'Merah Strip Hitam 1',
            'merah strip hitam 2'   => 'Merah Strip Hitam 2',
            'hitam'                 => 'Hitam',
        ];

        return view('ujian.daftar', [
            'eventUjian' => $eventUjian,
            'units'      => $units,
            'kelas'      => $kelas,
            'siswas'     => $siswas,
            'sabukList'  => $sabukList,
            'mode'       => 'create',
        ]);
    }

    /**
     * Simpan data pendaftaran ujian
     */
    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'siswa_id'           => 'required|exists:siswas,id',
            // 'nama_lengkap'      => 'required|string|max:255',
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'no_register'        => ['required', 'regex:/^[0-9]{15}$/'],
            'current_belt_level' => 'required|string|max:255',
            'next_belt_level'    => 'required|string|max:255',
            'units_id'           => 'required|exists:units,id',
            'kelas_id'           => 'required|exists:kelas,id',
        ]);
        // dd($request->all());

        $eventUjian = EventUjian::findOrFail($eventId);
        $siswa      = Siswa::findOrFail($validated['siswa_id']);

        // Sinkronkan data master siswa jika perlu (misalnya no_register)
        if (empty($siswa->no_register) && !empty($validated['no_register'])) {
            $siswa->update([
                'no_register' => $validated['no_register'],
            ]);
        }

        // Insert via relasi many-to-many (pivot)
        $eventUjian->siswa()->attach($siswa->id, [
            'nama_lengkap'       => $siswa->nama_lengkap,
            'jenis_kelamin'  =>      $validated['jenis_kelamin'],
            'tempat_lahir'       => $validated['tempat_lahir'],
            'tanggal_lahir'      => $validated['tanggal_lahir'],
            'no_register'        => $validated['no_register'],
            'units_id'           => $validated['units_id'],
            'kelas_id'           => $validated['kelas_id'],
            'current_belt_level' => $validated['current_belt_level'],
            'next_belt_level'    => $validated['next_belt_level'],
            'keterangan'         => 'on_proses',
        ]);

        return back()->with('success', 'Data berhasil didaftarkan! Status ujian: On Proses');
    }
}
