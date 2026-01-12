<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventUjian;
use App\Models\Siswa;
use App\Models\Unit;
use App\Models\Kelas;
use Carbon\Carbon;

class DaftarUjianController extends Controller
{
    /**
     * Form daftar ujian
     */
    public function create(string $slug)
    {
        $eventUjian = EventUjian::where('slug', $slug)->firstOrFail();

        // ⛔ Blok jika ujian sudah dimulai / lewat
        if (Carbon::today()->greaterThanOrEqualTo(
            Carbon::parse($eventUjian->tanggal_ujian)
        )) {
            return response()->view('ujian.ujian-berakhir', [
                'eventUjian' => $eventUjian,
            ], 403);
        }

        // 🔥 DATA SISWA UNTUK AUTOCOMPLETE (SAMA SEPERTI KEJUARAAN)
        $siswaJson = Siswa::with(['unit'])
            ->orderBy('nama_lengkap')
            ->get()
            ->map(function ($s) {
                return [
                    'id'                  => $s->id,
                    'nama'                => $s->nama_lengkap,
                    'tempat_lahir'        => $s->tempat_lahir,
                    'tanggal_lahir'       => $s->tanggal_lahir,
                    'no_register'         => $s->no_register,
                    'current_belt_level'  => $s->current_belt_level,
                    'next_belt_level'     => $s->next_belt_level,
                    'units_id'            => $s->units_id,
                    'kelas_id'            => $s->kelas_id,
                    'unit'                => $s->unit->name ?? '-',
                ];
            });

        return view('ujian.daftar', [
            'eventUjian' => $eventUjian,
            'units'      => Unit::orderBy('name')->get(),
            'kelas'      => Kelas::orderBy('name')->get(),
            'siswas'     => Siswa::orderBy('nama_lengkap')->get(), // boleh dihapus jika sudah tidak dipakai
            'siswaJson'  => $siswaJson, // ⭐ WAJIB
            'sabukList'  => [
                'putih'               => 'Putih',
                'kuning'              => 'Kuning',
                'kuning strip hijau'  => 'Kuning Strip Hijau',
                'hijau'               => 'Hijau',
                'hijau strip biru'    => 'Hijau Strip Biru',
                'biru'                => 'Biru',
                'biru strip merah'    => 'Biru Strip Merah',
                'merah'               => 'Merah',
                'merah strip hitam 1' => 'Merah Strip Hitam 1',
                'merah strip hitam 2' => 'Merah Strip Hitam 2',
                'hitam dan 1'               => 'Hitam DAN 1',
                'hitam dan 2'               => 'Hitam DAN 2',
                'hitam dan 3'               => 'Hitam DAN 3',
                'hitam dan 4'               => 'Hitam DAN 4',
                'hitam dan 5'               => 'Hitam DAN 5',
                'hitam dan 6'               => 'Hitam DAN 6',
                'hitam dan 7'               => 'Hitam DAN 7',
                'hitam dan 8'               => 'Hitam DAN 8',
                'hitam dan 9'               => 'Hitam DAN 9',
            ],
            'mode' => 'create',
        ]);
    }

    /**
     * Simpan data pendaftaran ujian
     */
    public function store(Request $request, string $slug)
    {
        $eventUjian = EventUjian::where('slug', $slug)->firstOrFail();

        // ⛔ Blok jika ujian sudah dimulai / lewat
        if (Carbon::today()->greaterThanOrEqualTo(
            Carbon::parse($eventUjian->tanggal_ujian)
        )) {
            abort(403, 'Ujian ini sudah berakhir.');
        }

        $validated = $request->validate([
            'siswa_id'           => 'required|exists:siswas,id',
            'jenis_kelamin'      => 'required|in:L,P',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'no_register'        => ['required', 'regex:/^[0-9]{13}$/'],
            'current_belt_level' => 'required|string',
            'next_belt_level'    => 'required|string',
            'units_id'           => 'required|exists:units,id',
            'kelas_id'           => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::findOrFail($validated['siswa_id']);

        // ⛔ Cegah siswa daftar dua kali
        if ($eventUjian->siswa()->where('siswa_id', $siswa->id)->exists()) {
            return back()->withErrors([
                'siswa_id' => 'Siswa ini sudah terdaftar pada ujian ini.',
            ]);
        }

        if (empty($siswa->no_register)) {
            $siswa->update([
                'no_register' => $validated['no_register'],
            ]);
        }

        $eventUjian->siswa()->attach($siswa->id, [
            'nama_lengkap'       => $siswa->nama_lengkap,
            'jenis_kelamin'      => $validated['jenis_kelamin'],
            'tempat_lahir'       => $validated['tempat_lahir'],
            'tanggal_lahir'      => $validated['tanggal_lahir'],
            'no_register'        => $validated['no_register'],
            'units_id'           => $validated['units_id'],
            'kelas_id'           => $validated['kelas_id'],
            'current_belt_level' => $validated['current_belt_level'],
            'next_belt_level'    => $validated['next_belt_level'],
            'keterangan'         => 'on_proses',
        ]);

        return back()->with('success', 'Data berhasil didaftarkan. Status: On Proses');
    }
}
