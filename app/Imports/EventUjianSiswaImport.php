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

    public function headingRow(): int
    {
        return 5; // baris header pertama
    }

    public function model(array $row)
    {
        // Abaikan baris kosong
        if (empty(array_filter($row))) {
            Log::warning('⚠️ Baris kosong dilewati.');
            return null;
        }

        // 🧭 Ambil hanya kolom yang diperlukan (abaikan kolom "no")
        $noRegister   = trim($row['no_register'] ?? '');
        $namaSiswa    = trim($row['nama_siswa'] ?? '');
        $sabukSaatIni = trim($row['sabuk_saat_ini'] ?? '');
        $nextBelt     = trim($row['geup_dan'] ?? '');
        $keterangan   = trim($row['keterangan'] ?? 'on_proses');

        Log::info('📥 Proses import baris:', [
            'nama_siswa' => $namaSiswa,
            'no_register' => $noRegister,
            'sabuk' => $sabukSaatIni,
            'next_belt' => $nextBelt,
            'keterangan' => $keterangan,
        ]);

        // 🔍 Cari siswa berdasar no_register atau nama
        $siswa = null;
        if (!empty($noRegister)) {
            $siswa = Siswa::where('no_register', $noRegister)->first();
        }

        if (!$siswa && !empty($namaSiswa)) {
            Log::warning("⚠️ NO REGISTER kosong atau tidak cocok, mencari berdasarkan nama: {$namaSiswa}");
            $siswa = Siswa::whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [strtolower(trim($namaSiswa))])->first();
        }

        if (!$siswa) {
            Log::warning("❌ Siswa tidak ditemukan: {$namaSiswa} ({$noRegister})");
            return null; // tetap lanjut ke baris berikutnya
        }

        // 🧩 Cek apakah siswa sudah terdaftar di pivot
        $exists = $this->eventUjian
            ->siswa()
            ->wherePivot('siswa_id', $siswa->id)
            ->exists();

        if ($exists) {
            $this->eventUjian->siswa()->updateExistingPivot($siswa->id, [
                'current_belt_level' => $sabukSaatIni,
                'next_belt_level'    => $nextBelt,
                'keterangan'         => $keterangan,
            ]);
            Log::info("♻️ Update data ujian untuk siswa {$siswa->nama_lengkap}");
        } else {
            $this->eventUjian->siswa()->attach($siswa->id, [
                'current_belt_level' => $sabukSaatIni,
                'next_belt_level'    => $nextBelt,
                'keterangan'         => $keterangan,
            ]);
            Log::info("✅ Tambah siswa {$siswa->nama_lengkap} ke event ID {$this->eventUjian->id}");
        }

        return null;
    }

    public function rules(): array
    {
        return [
            '*.nama_siswa' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_siswa.required' => 'Kolom NAMA SISWA wajib diisi.',
        ];
    }
}
