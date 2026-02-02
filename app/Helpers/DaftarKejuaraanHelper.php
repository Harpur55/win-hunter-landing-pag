<?php

namespace App\Helpers;

use App\Models\KejuaraanSiswa;
use App\Models\Siswa;

class DaftarKejuaraanHelper
{
    
    public static function isReguler(Siswa $siswa): bool
    {
        return $siswa->kelas?->tipe === 'reguler';
    }

    
    public static function totalKejuaraanTahunIni(Siswa $siswa): int
    {
        return KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    
    public static function pesanErrorReguler(?Siswa $siswa): ?string
    {
        if (! $siswa) return null;

        if (self::isReguler($siswa)) {
            $total = self::totalKejuaraanTahunIni($siswa);

            if ($total >= 2) {
                return 'Siswa reguler hanya boleh mengikuti maksimal 2 kejuaraan dalam 1 tahun.';
            }
        }

        return null;
    }

    public static function pakaiKuota(Siswa $siswa, bool $request): bool
    {
       
        if (self::isReguler($siswa)) {
            return false;
        }

      
        return $request === true;
    }

    /**
     * VALIDASI KUOTA
     */
    public static function validateKuota(Siswa $siswa, bool $pakaiKuota): void
    {
        if ($pakaiKuota && $siswa->sisa_kuota <= 0) {
            throw new \Exception('Kuota kelas sudah habis.');
        }
    }

   
    public static function bolehBatal(KejuaraanSiswa $data): bool
    {
        return $data->medali === null;
    }
}
