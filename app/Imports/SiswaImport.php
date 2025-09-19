<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Unit;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithHeadingRow
{
    private int $nisCounter;

    private array $sabukMap = 
    [
         'putih'               => 'putih',
    'kuning'              => 'kuning',
    'kuning strip hijau'  => 'kuning strip hijau',
    'hijau'               => 'hijau',
    'hijau strip biru'    => 'hijau strip biru',
    'biru'                => 'biru',
    'biru strip merah'    => 'biru strip merah',
    'merah'               => 'merah',
    'merah strip hitam 1' => 'merah strip hitam 1',
    'merah strip hitam 2' => 'merah strip hitam 2',
    'hitam'               => 'hitam',
          
    ];

    public function __construct()
    {
        $last = Siswa::latest('id')->first();
        $this->nisCounter = $last ? intval(substr($last->nis, 3)) : 0;
    }

    public function headingRow(): int
    {
        return 2; // Header ada di baris kedua (sesuai export)
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->nisCounter++;
            $nis = 'WH-' . str_pad($this->nisCounter, 3, '0', STR_PAD_LEFT);

            // Cari unit
            $unit = !empty($row['unit_latihan'] ?? null)
                ? Unit::where('name', 'LIKE', '%' . trim($row['unit_latihan']) . '%')->first()
                : null;
            $unitId = $unit?->id ?? 1;

            // Cari kelas
            $kelas = !empty($row['kelas'] ?? null)
                ? Kelas::where('name', 'LIKE', '%' . trim($row['kelas']) . '%')->first()
                : null;
            $kelasId = $kelas?->id ?? 1;

            // Normalisasi sabuk
            // $sabukRaw = strtolower(trim($row['sabuk'] ?? ''));
            $sabukRaw = strtolower(trim($row['sabuk'] ?? ''));
            $sabukFormatted = $this->sabukMap[$sabukRaw] ?? 'putih';

            // Parse tanggal
            $tanggal_lahir = $this->formatDateTime($row['tanggal_lahir'] ?? null);
            $tanggal_gabung = $this->formatDateTime($row['tanggal_bergabung'] ?? null);

            try {
                Siswa::create([
                    // 'nis'                          => $nis,
                    'nama_lengkap'                 => $row['nama_lengkap'] ?? '-',
                    'jenis_kelamin'                => $row['jenis_kelamin'] ?? '-',
                    'tempat_lahir'                 => $row['tempat_lahir'] ?? '-',
                    'tanggal_lahir'                => $tanggal_lahir,
                    'golongan_darah'               => $row['golongan_darah'] ?? '-',
                    'alamat_lengkap'               => $row['alamat'] ?? '-',
                    'no_telepon'                   => $row['no_telepon'] ?? '-',
                    'nama_ayah'                    => $row['nama_ayah'] ?? '-',
                    'pekerjaan_ayah'               => $row['pekerjaan_ayah'] ?? '-',
                    'nama_ibu'                     => $row['nama_ibu'] ?? '-',
                    'pekerjaan_ibu'                => $row['pekerjaan_ibu'] ?? '-',
                    'beladiri_yang_pernah_diikuti' => $row['beladiri_yang_pernah_diikuti'] ?? '-',
                    'status'                       => $row['status'] ?? 'Aktif',
                    'joint_date'                   => $tanggal_gabung,
                     'current_belt_level'          => $sabukFormatted,
                    'kelas_id'                     => $kelasId,
                    'units_id'                     => $unitId,
                    'created_at'                   => now(),
                    'updated_at'                   => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("❌ Gagal import siswa: " . json_encode($row) . " | Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Format tanggal ke Y-m-d H:i:s
     */
    private function formatDateTime($value)
    {
        try {
            if ($value === null || trim((string)$value) === '') {
                return null;
            }

            // Excel serial number
            if (is_numeric($value) && $value > 31_000) {
                $dt = ExcelDate::excelToDateTimeObject($value);
                return $dt->format('Y-m-d H:i:s');
            }

            // Unix timestamp
            if (is_numeric($value)) {
                return date('Y-m-d H:i:s', $value);
            }

            // String tanggal
            return Carbon::parse($value)->format('Y-m-d H:i:s');

        } catch (\Exception $e) {
            Log::error("❌ Gagal parse tanggal: {$value} | Error: " . $e->getMessage());
            return null;
        }
    }
}
