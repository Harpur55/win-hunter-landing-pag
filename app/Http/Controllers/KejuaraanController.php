<?php

namespace App\Http\Controllers;

use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use App\Models\Siswa;
use App\Services\KejuaraanQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class KejuaraanController extends Controller
{
    public function daftar(string $slug)
    {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        if ($kejuaraan->is_registration_closed) {
            abort(403, 'Pendaftaran kejuaraan sudah ditutup');
        }

        $siswas = Siswa::with(['unit', 'kelas'])
            ->orderBy('nama_lengkap')
            ->get();

        $siswaJson = $siswas->map(fn ($s) => [
            'id'       => $s->id,
            'nama'     => $s->nama_lengkap,
            'tempat'   => $s->tempat_lahir,
            'tanggal'  => optional($s->tanggal_lahir)?->format('Y-m-d'),
            'jk'       => $s->jenis_kelamin,
            'unit'     => $s->unit?->name ?? '-',
            'unit_id'  => $s->units_id,
            'sabuk'    => $s->current_belt_level,
            'kuota'    => $s->sisaKuota(),
        ]);

        return view(
            'kejuaraan.layouts.partials.DaftarKejuaraan',
            compact('kejuaraan', 'siswaJson')
        );
    }

    public function store(
        Request $request,
        string $slug,
        KejuaraanQuotaService $quotaService
    ) {
        $kejuaraan = Kejuaraan::where('slug', $slug)->firstOrFail();

        if ($kejuaraan->is_registration_closed) {
            abort(403, 'Pendaftaran kejuaraan sudah ditutup');
        }

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',

            'nama_lengkap'  => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',

            'units_id' => 'required|exists:units,id',
            'sabuk'    => 'required|string|max:100',

            'kategori_pertandingan' => 'required|in:kyorugi,poomsae',
            'use_kuota'             => 'required|boolean',

            // KYORUGI
            'berat_badan'  => 'required_if:kategori_pertandingan,kyorugi|nullable|numeric|min:1',
            'tinggi_badan' => 'required_if:kategori_pertandingan,kyorugi|nullable|numeric|min:1',

            // POOMSAE
            'tageuk'           => 'required_if:kategori_pertandingan,poomsae|nullable|string|max:50',
            'tingkat_kategori' => 'required_if:kategori_pertandingan,poomsae|nullable|string|max:50',
        ]);

        $siswa         = Siswa::findOrFail($validated['siswa_id']);
        $useKuota      = (bool) $validated['use_kuota'];
        $kategoriAtlit = $this->hitungKategoriUmur($validated['tanggal_lahir']);

        try {
            DB::transaction(function () use (
                $quotaService,
                $siswa,
                $kejuaraan,
                $validated,
                $useKuota,
                $kategoriAtlit
            ) {
                // validasi kuota
                $quotaService->validate($siswa, $useKuota);

                // cegah daftar kategori ganda
                $exists = KejuaraanSiswa::where([
                    'kejuaraan_id'        => $kejuaraan->id,
                    'siswa_id'            => $siswa->id,
                    'kategori_pertandingan' => $validated['kategori_pertandingan'],
                ])->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'kategori_pertandingan' =>
                            'Siswa sudah terdaftar pada kategori ini.',
                    ]);
                }

                KejuaraanSiswa::create([
                    'kejuaraan_id' => $kejuaraan->id,
                    'siswa_id'     => $siswa->id,
                    'units_id'     => $validated['units_id'],

                    'nama_lengkap'  => $validated['nama_lengkap'],
                    'tempat_lahir'  => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'sabuk'         => $validated['sabuk'],

                    'kategori_pertandingan' => $validated['kategori_pertandingan'],
                    'kategori_atlit'        => $kategoriAtlit,

                    'berat_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                        ? $validated['berat_badan']
                        : null,

                    'tinggi_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                        ? $validated['tinggi_badan']
                        : null,

                    'tageuk' => $validated['kategori_pertandingan'] === 'poomsae'
                        ? $validated['tageuk']
                        : null,

                    'tingkat_kategori' => $validated['kategori_pertandingan'] === 'poomsae'
                        ? $validated['tingkat_kategori']
                        : null,

                    'use_kuota' => $useKuota,
                    'periode'   => now()->year,
                ]);
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Peserta berhasil didaftarkan 🎉');
    }

    /**
     * HITUNG KATEGORI UMUR
     */
    private function hitungKategoriUmur(?string $tanggalLahir): ?string
    {
        if (!$tanggalLahir) {
            return null;
        }

        $umur = Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur >= 6  && $umur <= 11 => 'pracadet',
            $umur >= 12 && $umur <= 14 => 'cadet',
            $umur >= 15 && $umur <= 17 => 'junior',
            $umur >= 18                => 'senior',
            default                    => null,
        };
    }
}
