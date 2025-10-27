<?php

namespace App\Imports;

use App\Models\Kejuaraan;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;

class KejuaraanSiswaImport implements ToCollection
{
    protected Kejuaraan $event;

    public function __construct(Kejuaraan $event)
    {
        $this->event = $event;
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 0;

        foreach ($rows as $row) {
            $rowNumber++;

            // Lewati 5 baris pertama (judul & header)
            if ($rowNumber <= 5) {
                continue;
            }

            // Kolom ke-2 (index 1) adalah nama siswa
            $nama = trim($row[1] ?? '');

            if (empty($nama)) {
                Log::warning("⚠️ Baris {$rowNumber} dilewati karena nama kosong.");
                continue;
            }

            try {
                $siswa = Siswa::whereRaw('LOWER(nama_lengkap) = ?', [Str::lower($nama)])->first();

                if (!$siswa) {
                    Log::warning("⚠️ Siswa tidak ditemukan di baris {$rowNumber}: {$nama}");
                    continue;
                }

                // Normalisasi jenis kelamin (L/P)
                $jk = strtolower(trim($row[4] ?? ''));
                $jenis_kelamin = match (true) {
                    str_contains($jk, 'l') => 'L',
                    str_contains($jk, 'p') => 'P',
                    default => null,
                };

                // Konversi tanggal lahir
                $tanggal = null;
                if (!empty($row[3])) {
                    try {
                        $tanggal = Carbon::createFromFormat('d/m/Y', trim($row[3]))->format('Y-m-d');
                    } catch (\Throwable $e) {
                        Log::warning("⚠️ Format tanggal tidak valid di baris {$rowNumber}: {$row[3]}");
                    }
                }

                // Data pivot untuk tabel kejuaraan_siswa
                $pivotData = [
                    'nama_lengkap'          => $siswa->nama_lengkap,
                    'tempat_lahir'          => $row[2] ?? $siswa->tempat_lahir,
                    'tanggal_lahir'         => $tanggal ?? $siswa->tanggal_lahir,
                    'jenis_kelamin'         => $jenis_kelamin,
                    'sabuk'                 => $row[5] ?? $siswa->current_belt_level,
                    'kategori_pertandingan' => $row[6] ?? null,
                    'berat_badan'           => $row[7] ?? null,
                    'tinggi_badan'          => $row[8] ?? null,
                    'kategori_atlit'        => $row[9] ?? null,
                    'tingkat_kategori'      => $row[10] ?? null,
                    'medali'                => strtolower($row[11] ?? 'tidak_ada'),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];

                $this->event->siswa()->syncWithoutDetaching([
                    $siswa->id => $pivotData,
                ]);

                Log::info("✅ [Row {$rowNumber}] {$nama} berhasil disimpan ke kejuaraan_siswa.");
            } catch (\Throwable $e) {
                Log::error("❌ Gagal import baris {$rowNumber}: {$e->getMessage()}");
            }
        }
    }
}
