<?php

namespace App\Http\Controllers;

use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use App\Models\Siswa;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KejuaraanController extends Controller
{
    /**
     * Tampilkan form pendaftaran kejuaraan
     */
    public function daftar(string $slug)
    {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        // Optional: tutup pendaftaran
        if ($kejuaraan->is_registration_closed) {
            abort(403, 'Pendaftaran kejuaraan sudah ditutup');
        }

        return view('kejuaraan.layouts.partials.DaftarKejuaraan', [
            'kejuaraan' => $kejuaraan,
            'siswas'    => Siswa::orderBy('nama_lengkap')->get(),
            'units'     => Unit::orderBy('name')->get(),
        ]);
    }

    /**
     * Simpan pendaftaran kejuaraan
     */
    public function store(Request $request, string $slug)
    {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            // mode input
            'input_mode' => 'required|in:db,manual',

            // siswa
            'siswa_id' => 'nullable|required_if:input_mode,db|exists:siswas,id',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',

            // unit
            'units_id' => 'required|exists:units,id',

            // pertandingan
            'sabuk' => 'required|string|max:100',
            'kategori_pertandingan' => 'required|in:kyorugi,poomsae',
            'kategori_atlit' => 'required|string|max:50',

            // kyorugi
            'berat_badan' => 'nullable|required_if:kategori_pertandingan,kyorugi|numeric',
            'tinggi_badan' => 'nullable|required_if:kategori_pertandingan,kyorugi|numeric',
            'kelas_berat'  => 'nullable|required_if:kategori_pertandingan,kyorugi',

            // poomsae
            'tageuk' => 'nullable|required_if:kategori_pertandingan,poomsae',
            'tingkat_kategori' => 'nullable|required_if:kategori_pertandingan,poomsae',
        ]);

        /**
         * Cegah duplikasi
         */
        $exists = KejuaraanSiswa::where('kejuaraan_id', $kejuaraan->id)
            ->where('nama_lengkap', $validated['nama_lengkap'])
            ->where('kategori_pertandingan', $validated['kategori_pertandingan'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'nama_lengkap' => 'Peserta sudah terdaftar pada kategori ini.'
            ])->withInput();
        }

        /**
         * Simpan ke tabel kejuaraan_siswa
         */
        KejuaraanSiswa::create([
            'kejuaraan_id' => $kejuaraan->id,
            'siswa_id'     => $validated['siswa_id'] ?? null,
            'units_id'     => $validated['units_id'],

            'nama_lengkap'  => $validated['nama_lengkap'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],

            'sabuk' => $validated['sabuk'],
            'kategori_pertandingan' => $validated['kategori_pertandingan'],
            'kategori_atlit' => $validated['kategori_atlit'],

            // kyorugi
            'berat_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? $validated['berat_badan']
                : null,

            'tinggi_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? $validated['tinggi_badan']
                : null,

            'kelas_berat' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? $validated['kelas_berat']
                : null,

            // poomsae
            'tageuk' => $validated['kategori_pertandingan'] === 'poomsae'
                ? $validated['tageuk']
                : null,

            'tingkat_kategori' => $validated['kategori_pertandingan'] === 'poomsae'
                ? $validated['tingkat_kategori']
                : null,

            // default
            'medali' => 'tidak_ada',
            'use_kuota' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Peserta berhasil didaftarkan ke kejuaraan 🎉');
    }
}
