<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EventUjianSiswaImport implements ToCollection
{
    protected $eventUjian;

    public function __construct($eventUjian)
    {
        $this->eventUjian = $eventUjian;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip header (baris 1-8)
            if ($index < 8) {
                continue;
            }

            // Kolom sesuai file excel
            $nama      = $row[1] ?? null; // B = Nama Siswa
            $unit      = $row[2] ?? null; // C = Unit Latihan
            $kelas     = $row[3] ?? null; // D = Kelas
            $tempat    = $row[4] ?? null; // E = Tempat Lahir
            $tglLahir  = $row[5] ?? null; // F = Tanggal Lahir
            $sabukNow  = $row[6] ?? null; // G = Sabuk Saat Ini
            $sabukNext = $row[7] ?? null; // H = Sabuk Berikutnya
            $status    = $row[8] ?? 'on_proses'; // I = Keterangan

            if (!$nama) continue;

            // Cari siswa berdasarkan nama (atau lebih baik NIS kalau ada)
            $siswa = Siswa::where('nama_lengkap', $nama)->first();
            if (!$siswa) {
                continue;
            }

            // Cegah duplikasi
            if ($this->eventUjian->siswa()->where('siswa_id', $siswa->id)->exists()) {
                continue;
            }

            // Simpan ke pivot
            $this->eventUjian->siswa()->attach($siswa->id, [
                'current_belt_level' => $sabukNow,
                'next_belt_level'    => $sabukNext,
                'keterangan'         => strtolower($status) ?: 'on_proses',
            ]);
        }
    }
}
