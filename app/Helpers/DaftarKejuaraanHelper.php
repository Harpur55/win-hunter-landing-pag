<?php

namespace App\Helpers;

use App\Models\KejuaraanSiswa;
use App\Models\Siswa;

class DaftarKejuaraanhelper
{
    /**
     * Cek apakah siswa kelas REGULER
     */
    public static function isReguler(?Siswa $siswa): bool
    {
        if (! $siswa || ! $siswa->kelas) {
            return false;
        }

        return strtolower((string) $siswa->kelas->nama) === 'reguler';
    }

    /**
     * Hitung jumlah kejuaraan yang diikuti siswa dalam 1 tahun
     */
    public static function jumlahKejuaraanTahunIni(Siswa $siswa): int
    {
        return KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /**
     * Apakah siswa REGULER masih boleh daftar kejuaraan
     * (maks 2 per tahun)
     */
    public static function bolehDaftarReguler(Siswa $siswa): bool
    {
        return self::jumlahKejuaraanTahunIni($siswa) < 2;
    }

    /**
     * Tentukan apakah pendaftaran menggunakan kuota kelas
     */
    public static function pakaiKuota(Siswa $siswa, bool $pilihanUser): bool
    {
        // REGULER TIDAK PERNAH PAKAI KUOTA
        if (self::isReguler($siswa)) {
            return false;
        }

        // Prestasi / Khusus ikut pilihan user
        return $pilihanUser;
    }

    /**
     * Validasi batas REGULER (return message jika gagal)
     */
    public static function pesanErrorReguler(Siswa $siswa): ?string
    {
        if (! self::isReguler($siswa)) {
            return null;
        }

        if (! self::bolehDaftarReguler($siswa)) {
            return 'Siswa kelas reguler hanya boleh mengikuti maksimal 2 kejuaraan dalam 1 tahun.';
        }

        return null;
    }
}
