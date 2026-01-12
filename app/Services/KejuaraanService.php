<?php

namespace App\Services;

use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KejuaraanService
{
    public function daftar(
        Kejuaraan $kejuaraan,
        array $validated,
        bool $useKuota
    ): KejuaraanSiswa {

        return DB::transaction(function () use ($kejuaraan, $validated, $useKuota) {

            // 🔒 Lock siswa + kelas
            $siswa = Siswa::with('kelas')
                ->lockForUpdate()
                ->findOrFail($validated['siswa_id']);

            if (! $siswa->kelas) {
                throw ValidationException::withMessages([
                    'nama_lengkap' => 'Kelas siswa tidak ditemukan.',
                ]);
            }

            $namaKelas = strtolower($siswa->kelas->name);
            $tahun     = now()->year;

            /**
             * =========================
             * 🥇 PRESTASI → OPSIONAL KUOTA
             * =========================
             */
            if (str_contains($namaKelas, 'prestasi') && $useKuota) {

                if ((int) $siswa->kelas->kuota <= 0) {
                    throw ValidationException::withMessages([
                        'nama_lengkap' => 'Kuota kelas prestasi sudah habis.',
                    ]);
                }

                $siswa->kelas->decrement('kuota');
            }

            /**
             * =========================
             * 🥈 REGULER → MAX 2 / TAHUN
             * =========================
             */
            if (str_contains($namaKelas, 'reguler')) {

                $jumlah = $siswa->kejuaraan()
                    ->whereYear('kejuaraan_siswa.created_at', $tahun)
                    ->count();

                if ($jumlah >= 2) {
                    throw ValidationException::withMessages([
                        'nama_lengkap' =>
                            'Siswa reguler maksimal 2 kali kejuaraan per tahun.',
                    ]);
                }
            }

            /**
             * =========================
             * 💾 SIMPAN
             * =========================
             */
            return KejuaraanSiswa::create([
                'kejuaraan_id' => $kejuaraan->id,
                'siswa_id'     => $validated['siswa_id'],
                'units_id'     => $validated['units_id'],

                'nama_lengkap'  => $validated['nama_lengkap'],
                'tempat_lahir'  => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'sabuk'         => $validated['sabuk'],

                'kategori_pertandingan' => $validated['kategori_pertandingan'],
                'kategori_atlit'        => $validated['kategori_atlit'] ?? null,

                'berat_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                    ? ($validated['berat_badan'] ?? null)
                    : null,

                'tinggi_badan' => $validated['kategori_pertandingan'] === 'kyorugi'
                    ? ($validated['tinggi_badan'] ?? null)
                    : null,

                'kelas_berat' => $validated['kategori_pertandingan'] === 'kyorugi'
                    ? ($validated['kelas_berat'] ?? null)
                    : null,

                'tageuk' => $validated['kategori_pertandingan'] === 'poomsae'
                    ? ($validated['tageuk'] ?? null)
                    : null,

                'tingkat_kategori' => $validated['kategori_pertandingan'] === 'poomsae'
                    ? ($validated['tingkat_kategori'] ?? null)
                    : null,

                'medali'    => 'tidak_ada',
                'use_kuota' => $useKuota,
                'periode'   => $tahun,
            ]);
        });
    }

    /**
     * Rollback kuota jika dihapus
     */
    public function rollbackKuota(KejuaraanSiswa $kejuaraanSiswa): void
    {
        DB::transaction(function () use ($kejuaraanSiswa) {

            if (! $kejuaraanSiswa->use_kuota) {
                $kejuaraanSiswa->delete();
                return;
            }

            $siswa = Siswa::with('kelas')
                ->lockForUpdate()
                ->find($kejuaraanSiswa->siswa_id);

            if ($siswa && $siswa->kelas) {
                $siswa->kelas->increment('kuota');
            }

            $kejuaraanSiswa->delete();
        });
    }
}
