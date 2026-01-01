<?php

namespace App\Http\Controllers;

use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KejuaraanController extends Controller
{
    /**
     * Form pendaftaran kejuaraan
     */
    public function daftar(string $slug)
    {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        if ($kejuaraan->is_registration_closed) {
            abort(403, 'Pendaftaran kejuaraan sudah ditutup');
        }

        $siswas = Siswa::with('unit')
            ->orderBy('nama_lengkap')
            ->get();

        $siswaJson = $siswas->map(fn ($s) => [
            'id'      => $s->id,
            'nama'    => $s->nama_lengkap,
            'tempat'  => $s->tempat_lahir,
            'tanggal' => $s->tanggal_lahir,
            'jk'      => $s->jenis_kelamin,
            'unit'    => $s->unit?->name ?? '-',
            'unit_id' => $s->units_id,
            'sabuk'   => $s->current_belt_level,
        ]);

        return view('kejuaraan.layouts.partials.DaftarKejuaraan', [
            'kejuaraan' => $kejuaraan,
            'siswaJson' => $siswaJson,
        ]);
    }

    /**
     * Simpan pendaftaran
     */
    public function store(Request $request, string $slug)
    {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',

            'nama_lengkap'  => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'units_id'      => 'required|exists:units,id',
            'sabuk'         => 'required|string|max:100',

            'kategori_pertandingan' => 'required|in:kyorugi,poomsae',

            // ✅ BOLEH KOSONG
            'kategori_atlit' => 'nullable|in:Pro,Regular',

            // ✅ KYORUGI (OPSIONAL)
            'berat_badan'  => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'kelas_berat'  => 'nullable|string|max:50',

            // ✅ POOMSAE (OPSIONAL)
            'tageuk'            => 'nullable|string|max:50',
            'tingkat_kategori'  => 'nullable|string|max:50',
        ]);

        // ⛔ Cegah duplikasi
        $exists = KejuaraanSiswa::where('kejuaraan_id', $kejuaraan->id)
            ->where('siswa_id', $validated['siswa_id'])
            ->where('kategori_pertandingan', $validated['kategori_pertandingan'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['nama_lengkap' => 'Siswa sudah terdaftar pada kategori ini.'])
                ->withInput();
        }

        // ✅ SIMPAN (AMAN)
        KejuaraanSiswa::create([
            'kejuaraan_id' => $kejuaraan->id,
            'siswa_id'     => $validated['siswa_id'],
            'units_id'     => $validated['units_id'],

            'nama_lengkap'  => $validated['nama_lengkap'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'sabuk'         => $validated['sabuk'],

            'kategori_pertandingan' => $validated['kategori_pertandingan'],

            // 🥋 KATEGORI ATLIT (BOLEH UNTUK KEDUA)
            'kategori_atlit' => $validated['kategori_atlit'] ?? null,

            // 🥊 KYORUGI
            'berat_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? ($validated['berat_badan'] ?? null)
                : null,

            'tinggi_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? ($validated['tinggi_badan'] ?? null)
                : null,

            'kelas_berat' => $validated['kategori_pertandingan'] === 'kyorugi'
                ? ($validated['kelas_berat'] ?? null)
                : null,

            // 🧘 POOMSAE
            'tageuk' => $validated['kategori_pertandingan'] === 'poomsae'
                ? ($validated['tageuk'] ?? null)
                : null,

            'tingkat_kategori' => $validated['kategori_pertandingan'] === 'poomsae'
                ? ($validated['tingkat_kategori'] ?? null)
                : null,

            'medali'    => 'tidak_ada',
            'use_kuota' => true,
        ]);

        return back()->with('success', 'Peserta berhasil didaftarkan 🎉');
    }
}
