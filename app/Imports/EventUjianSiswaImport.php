<?php

namespace App\Imports;

use App\Models\EventUjian;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class EventUjianSiswaImport implements ToCollection, WithHeadingRow, WithValidation, WithStartRow
{
    protected $eventUjian;

    public function __construct(EventUjian $eventUjian)
    {
        $this->eventUjian = $eventUjian;
    }

    public function startRow(): int
    {
        return 9; // Tetap 9, karena header di baris 8 dan data mulai di baris 9
    }

   public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $this->startRow() + $index;

            $namaSiswaExcel = trim($row['NAMA SISWA'] ?? '');

            // --- POSISI DD YANG PALING UMUM DAN EFEKTIF UNTUK DEBUGGING BARIS PER BARIS ---
            // Letakkan dd() setelah variabel didefinisikan atau setelah Anda melakukan operasi penting padanya.
            // Jika Anda ingin melihat nilai dari baris tertentu, Anda bisa tambahkan kondisi.

            // Contoh 1: Lihat nilai namaSiswaExcel untuk baris pertama data (baris 9 di Excel)
            if ($lineNumber === 9) {
                dd([
                    'Baris Excel' => $lineNumber,
                    'Nilai Mentah NAMA SISWA (dari $row)' => $row['NAMA SISWA'] ?? null, // Untuk melihat sebelum trim
                    'Nilai Setelah Trim' => $namaSiswaExcel,
                    'Panjang Setelah Trim' => strlen($namaSiswaExcel),
                    'Is Empty?' => empty($namaSiswaExcel),
                    'Tipe Data' => gettype($namaSiswaExcel),
                ]);
            }
            // HAPUS dd() ini atau komentari setelah Anda mendapatkan informasi yang Anda butuhkan,
            // karena ini akan menghentikan eksekusi setiap kali baris 9 diproses.

            // Contoh 2: Jika Anda ingin melihat apakah query pencarian siswa berhasil
            $siswa = Siswa::where('nama_lengkap', $namaSiswaExcel)->first();

            if ($lineNumber === 9 && !$siswa) { // Debug jika siswa tidak ditemukan pada baris 9
                dd([
                    'Baris Excel' => $lineNumber,
                    'Nama Siswa Dicari' => $namaSiswaExcel,
                    'Siswa Ditemukan di DB' => $siswa, // Akan null jika tidak ditemukan
                    'Pesan Debug' => 'Siswa tidak ditemukan di database untuk nama ini.',
                ]);
            }

            // 3. Attach siswa ke event ujian dengan data pivot
            try {
                $this->eventUjian->siswas()->attach($siswa->id, [
                    'current_belt_level' => $currentBeltLevelFromSiswa, // Mengambil dari data siswa
                    'next_belt_level' => $nextBeltLevelFromSiswa,       // Mengambil dari data siswa
                    'keterangan' => $keterangan,                        // Tetap dari Excel jika diperlukan
                ]);
                Log::info("Import Success: Siswa '{$siswa->nama_lengkap}' (ID: {$siswa->id}, Baris Excel: {$lineNumber}) berhasil dihubungkan ke Event Ujian '{$this->eventUjian->nama_ujian}'.");
            } catch (\Exception $e) {
                Log::error("Import Error: Gagal menghubungkan siswa '{$siswa->nama_lengkap}' (ID: {$siswa->id}, Baris Excel: {$lineNumber}) ke Event Ujian '{$this->eventUjian->nama_ujian}'. Error: " . $e->getMessage());
            }
        }
    }

    public function rules(): array
    {
        return [
            'NAMA SISWA' => [
                'required',
                'string',
                function($attribute, $value, $fail) {
                    if (!Siswa::where('nama_lengkap', trim($value))->exists()) {
                        $fail("Siswa dengan nama '{$value}' tidak ditemukan di database.");
                    }
                },
            ],
            // Hapus aturan validasi untuk 'TINGKAT SABUK SAAT INI (SEBELUM UKT)'
            // dan 'TINGKAT SABUK BERIKUTNYA (SESUDAH UKT)' dari sini
            // karena kita tidak lagi mengambilnya dari Excel.
            'KET' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'NAMA SISWA.required' => 'Kolom "NAMA SISWA" harus diisi.',
            'NAMA SISWA.string' => 'Kolom "NAMA SISWA" harus berupa teks.',
            'NAMA SISWA.exists' => 'Siswa dengan nama ini tidak ditemukan di database.',
            'KET.string' => 'Kolom "KET" harus berupa teks.',
        ];
    }
}