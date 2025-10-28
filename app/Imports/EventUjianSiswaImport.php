<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\EventUjian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;



class EventUjianSiswaImport implements ToCollection, WithHeadingRow
{
    protected EventUjian $eventUjian;

    public function __construct(EventUjian $eventUjian)
    {
        $this->eventUjian = $eventUjian;
    }

    public function headingRow(): int
    {
        return 5; // baris header di Excel
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // 🔧 Normalisasi header agar lowercase dan underscore
            $normalized = collect($row)->mapWithKeys(function ($value, $key) {
                $key = strtolower(trim($key));
                $key = preg_replace('/\s+/u', '_', $key);
                $key = str_replace(['/', '\\', '.', '-', '(', ')'], '_', $key);
                return [$key => trim($value)];
            });

            Log::info('🧩 Header keys normalisasi:', $normalized->keys()->toArray());

            if (empty(array_filter($normalized->toArray()))) {
                continue;
            }

            $noRegister   = trim($normalized['no_register'] ?? '');
            $namaSiswa    = trim($normalized['nama_siswa'] ?? '');
            $sabukSaatIni = strtolower(trim($normalized['sabuk_saat_ini'] ?? ''));
            $geup         = trim($normalized['geup_dan'] ?? $normalized['geup__dan'] ?? '');
            $keterangan   = trim($normalized['keterangan'] ?? 'on_proses');

            // 🟢 Deteksi "SABUK BERIKUTNYA" lebih cerdas (backup posisi kolom)
            $nextBeltKey = collect($normalized->keys())->first(function ($key) {
                return preg_match('/sabuk.*berikut/i', $key) || preg_match('/berikut.*sabuk/i', $key);
            });

            $nextBelt = strtolower(trim($normalized[$nextBeltKey] ?? ''));

            // fallback jika key kosong → ambil kolom terakhir
            if (empty($nextBelt) && count($normalized) >= 9) {
                $nextBelt = strtolower(trim(array_values($normalized->toArray())[8] ?? ''));
                $nextBeltKey = 'auto_fallback_col';
            }

            Log::info("🎯 Next Belt Key: {$nextBeltKey} | Value: {$nextBelt}");

            if (empty($namaSiswa)) {
                continue;
            }

            // cari siswa
            $siswa = null;
            if (!empty($noRegister)) {
                $siswa = Siswa::where('no_register', $noRegister)->first();
            }
            if (!$siswa && !empty($namaSiswa)) {
                $siswa = Siswa::whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [strtolower($namaSiswa)])->first();
            }

            if (!$siswa) {
                Log::warning("⚠️ Siswa '$namaSiswa' tidak ditemukan, dilewati.");
                continue;
            }

            // simpan ke pivot
            DB::table('event_ujian_siswa')->updateOrInsert(
                [
                    'event_ujian_id' => $this->eventUjian->id,
                    'siswa_id'       => $siswa->id,
                ],
                [
                    'current_belt_level' => $sabukSaatIni,
                    'next_belt_level'    => $nextBelt,
                    'geup'               => $geup,
                    'keterangan'         => $keterangan,
                    'updated_at'         => now(),
                    'created_at'         => now(),
                ]
            );

            Log::info("✅ Data tersimpan / diperbarui untuk siswa {$siswa->nama_lengkap} | Next Belt: {$nextBelt}");
        }
Notification::make()
    ->title('✅ Import Berhasil')
    ->body('Data siswa ujian telah disimpan ke database termasuk sabuk berikutnya.')
    ->success()
    // ->sendToDatabase(false) 
    ->send(); 
    }
}
