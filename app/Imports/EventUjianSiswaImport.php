<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\EventUjian;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EventUjianSiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected EventUjian $eventUjian;

    public function __construct(EventUjian $eventUjian)
    {
        $this->eventUjian = $eventUjian;
    }

    /**
     * Header tabel ada di baris ke-5 (setelah judul ujian).
     */
    public function headingRow(): int
    {
        return 5;
    }

    /**
     * Proses setiap baris dari file Excel.
     */
    public function model(array $row)
    {
        Log::info('Row Import Ujian:', $row);

        // ✅ Cari siswa berdasarkan NO REGISTER
        $siswa = Siswa::where('no_register', $row['no_register'] ?? null)->first();

        if (!$siswa) {
            Log::warning("Siswa tidak ditemukan: " . json_encode($row));
            return null;
        }

        // Simpan/Update relasi pivot siswa <-> event ujian
        $this->eventUjian->siswa()->syncWithoutDetaching([
            $siswa->id => [
                'current_belt_level' => $row['sabuk_saat_ini'] ?? null,
                'next_belt_level'    => $row['sabuk_berikutnya'] ?? null,
                'keterangan'         => $row['keterangan'] ?? null,
            ]
        ]);

        return null;
    }

    /**
     * Validasi isi file Excel.
     */
    public function rules(): array
    {
        return [
            '*.nama_siswa'   => 'required|string',
            '*.no_register'  => 'required|string',
            ''
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_siswa.required'   => 'Nama siswa wajib diisi.',
            '*.no_register.required'  => 'No register wajib diisi.',
        ];
    }
}
