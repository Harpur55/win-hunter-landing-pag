<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * FORM INPUT
     */
    public function inputDataSiswa()
    {
        return view('StoredataSiswa.layouts.form-insert', [
            'kelas'       => Kelas::all(),
            'units'       => Unit::all(),
            'beltOptions' => self::beltOptions(),
        ]);
    }

    /**
     * CREATE / UPDATE (SATU PINTU)
     */
    public function storeOrUpdate(Request $request)
{
    $validated = $request->validate([
        'siswa_id'        => 'nullable|exists:siswas,id',
        'nama_lengkap'    => 'required|string|max:255',
        'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
        'units_id'        => 'required|exists:units,id',
        'kelas_id'        => 'required|exists:kelas,id',
        'current_belt_level' => 'required|string|max:100',
        'no_register'     => 'nullable|string|max:18',
        'tempat_lahir'    => 'required|string|max:255',
        'tanggal_lahir'   => 'required|date',
        'no_telepon'      => 'required|string|max:20',
        'alamat_lengkap'  => 'required|string',
        'nama_ayah'       => 'required|string|max:255',
        'pekerjaan_ayah'  => 'nullable|string|max:255',
        'nama_ibu'        => 'nullable|string|max:255',
        'pekerjaan_ibu'   => 'nullable|string|max:255',
        'joint_date'      => 'nullable|date',
        'status'          => 'required|in:Aktif,Nonaktif',
        'image'           => 'nullable|image|max:2048',
    ]);

    DB::transaction(function () use ($validated, $request) {

        // 🔑 UPDATE JIKA ADA siswa_id
        if (!empty($validated['siswa_id'])) {

            $siswa = Siswa::lockForUpdate()->findOrFail($validated['siswa_id']);

            unset($validated['siswa_id']); // jangan overwrite ID

            $siswa->update($validated);

        } 
        // 🆕 CREATE JIKA TIDAK ADA siswa_id
        else {

            // Validasi no_register unik
            if (!empty($validated['no_register']) &&
                Siswa::where('no_register', $validated['no_register'])->exists()) {
                throw new \Exception('No Register sudah digunakan');
            }

            $siswa = Siswa::create($validated);
        }

        // Upload foto
        if ($request->hasFile('image')) {
            $siswa->image = $request->file('image')
                ->store('siswa_images', 'public');
            $siswa->save();
        }
    });

    return back()->with('success', 'Data siswa berhasil disimpan.');
}

    /**
     * AUTOFILL BY NAME
     */
   public function searchByName(Request $request)
{
    $name = trim($request->nama);

    if (strlen($name) < 5) {
        return response()->json(['found' => false]);
    }

    $siswa = Siswa::whereRaw(
        'LOWER(nama_lengkap) = ?',
        [strtolower($name)]
    )->first();

    if (! $siswa) {
        return response()->json(['found' => false]);
    }

    return response()->json([
        'found' => true,
        'data' => [
            'id' => $siswa->id,
            'no_register' => $siswa->no_register,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'tempat_lahir' => $siswa->tempat_lahir,
            'tanggal_lahir' => optional($siswa->tanggal_lahir)->format('Y-m-d'),
            'alamat_lengkap' => $siswa->alamat_lengkap,
            'no_telepon' => $siswa->no_telepon,
            'current_belt_level' => $siswa->current_belt_level,
            'units_id' => $siswa->units_id,
            'kelas_id' => $siswa->kelas_id,
        ]
    ]);
}

    /**
     * LIST SABUK
     */
    private static function beltOptions(): array
    {
        return [
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
            'hitam'               => 'Hitam',
        ];
    }
}
