<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Validation\ValidationException;

class KejuaraanQuotaService
{
    /**
     * Validasi kuota & aturan kelas sebelum daftar kejuaraan
     *
     * @throws ValidationException
     */
    public function validate(Siswa $siswa, bool $useKuota): void
    {
        if (! $siswa->kelas) {
            throw ValidationException::withMessages([
                'kelas' => 'Siswa belum terdaftar pada kelas.',
            ]);
        }

        $namaKelas = strtolower($siswa->kelas->name);
        $tahun     = now()->year;

        /**
         * ==================================================
         * 🟥 RULE 1 — KELAS REGULER (WAJIB)
         * ==================================================
         * - Maksimal 2 kejuaraan / tahun
         * - Berlaku walaupun tidak pakai kuota
         */
        if (str_contains($namaKelas, 'reguler')) {

            $jumlah = $siswa->kejuaraan()
                ->where('periode', $tahun)
                ->count();

            if ($jumlah >= 2) {
                throw ValidationException::withMessages([
                    'kuota' =>
                        'Siswa kelas reguler hanya boleh mengikuti maksimal 2 kejuaraan dalam satu tahun.',
                ]);
            }
        }

        /**
         * ==================================================
         * 🟦 RULE 2 — VALIDASI KUOTA (OPSIONAL)
         * ==================================================
         * - Hanya dicek jika use_kuota = true
         * - Kuota dihitung DINAMIS
         */
        if ($useKuota === true) {

            $sisaKuota = $siswa->sisaKuota();

            if ($sisaKuota <= 0) {
                throw ValidationException::withMessages([
                    'kuota' => 'Kuota kejuaraan siswa sudah habis.',
                ]);
            }
        }

        /**
         * ==================================================
         * 🟨 RULE 3 — DUPLIKASI EVENT (OPSIONAL TAPI AMAN)
         * ==================================================
         * - 1 siswa tidak boleh daftar event yang sama 2x
         */
        if (request()->filled('kejuaraan_id')) {

            $exists = $siswa->kejuaraan()
                ->where('kejuaraan_id', request('kejuaraan_id'))
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'kejuaraan' => 'Siswa sudah terdaftar pada kejuaraan ini.',
                ]);
            }
        }
    }
}
