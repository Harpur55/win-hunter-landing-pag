<?php

namespace App\Imports;

use App\Models\Kejuaraan;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
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

        // 🎯 Tentukan tahun event dan tahun sekarang
        $eventYear = Carbon::parse($this->event->tanggal_mulai)->year ?? null;
        $currentYear = now()->year;

        // Jika tahun event < tahun sekarang → dianggap event lampau
        $isEventLampau = $eventYear && $eventYear < $currentYear;

        foreach ($rows as $row) {
            $rowNumber++;

            // Lewati header baris pertama
            if ($rowNumber <= 6) continue;

            $nama = trim($row[1] ?? '');
            if (empty($nama)) {
                Log::warning("⚠️ Baris {$rowNumber} dilewati karena nama kosong.");
                continue;
            }

            try {
                $siswa = Siswa::whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [strtolower($nama)])
                    ->orWhere('nama_lengkap', 'LIKE', '%' . $nama . '%')
                    ->first();

                if (!$siswa) {
                    Log::warning("⚠️ Siswa tidak ditemukan di baris {$rowNumber}: {$nama}");
                    continue;
                }

                // Jenis kelamin
                $jk = strtolower(trim($row[4] ?? ''));
                $jenis_kelamin = match (true) {
                    str_contains($jk, 'l') => 'L',
                    str_contains($jk, 'p') => 'P',
                    default => null,
                };

                // Tanggal lahir
                $tanggal = null;
                if (!empty($row[3])) {
                    if (is_numeric($row[3])) {
                        $tanggal = Date::excelToDateTimeObject($row[3])->format('Y-m-d');
                    } else {
                        try {
                            $tanggal = Carbon::createFromFormat('d/m/Y', trim($row[3]))->format('Y-m-d');
                        } catch (\Throwable $e) {
                            Log::warning("⚠️ Format tanggal tidak valid di baris {$rowNumber}: {$row[3]}");
                        }
                    }
                }

                $berat_badan = is_numeric($row[10]) ? $row[10] : null;
                $tinggi_badan = is_numeric($row[11]) ? $row[11] : null;
                $medali = ($row[12] == '-' || empty($row[12])) ? null : strtolower(trim($row[12]));

                $pivotData = [
                    'nama_lengkap'          => $siswa->nama_lengkap,
                    'tempat_lahir'          => trim($row[2] ?? $siswa->tempat_lahir),
                    'tanggal_lahir'         => $tanggal ?? $siswa->tanggal_lahir,
                    'jenis_kelamin'         => $jenis_kelamin,
                    'sabuk'                 => trim($row[5] ?? $siswa->current_belt_level),
                    'kategori_pertandingan' => trim($row[6] ?? null),
                    'tingkat_kategori'      => trim($row[8] ?? null),
                    'kategori_atlit'        => trim($row[9] ?? null),
                    'berat_badan'           => $berat_badan,
                    'tinggi_badan'          => $tinggi_badan,
                    'medali'                => $medali,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];

                // Simpan ke pivot table
                $this->event->siswa()->syncWithoutDetaching([
                    $siswa->id => $pivotData,
                ]);

                // 🎯 Kurangi kuota hanya jika event tahun ini / mendatang
                if (!$isEventLampau && $siswa->sisa_kuota > 0) {
                    $siswa->decrement('sisa_kuota');
                    Log::info("✅ Kuota siswa {$siswa->nama_lengkap} dikurangi (tahun {$eventYear}, sisa: {$siswa->sisa_kuota}).");
                } else {
                    Log::info("ℹ️ Event lampau (tahun {$eventYear}), kuota siswa {$siswa->nama_lengkap} tidak dikurangi.");
                }

            } catch (\Throwable $e) {
                Log::error("❌ Gagal import baris {$rowNumber}: {$e->getMessage()}");
            }
        }
    }
}
