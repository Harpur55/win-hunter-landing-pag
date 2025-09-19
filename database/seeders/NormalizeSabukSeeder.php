<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

class NormalizeSabukSeeder extends Seeder
{
    public function run(): void
    {
        $sabukMap = [
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

        $siswas = Siswa::all();
        $countUpdated = 0;

        foreach ($siswas as $siswa) {
            $raw = strtolower(trim($siswa->current_belt_level ?? ''));
            $normalized = $sabukMap[$raw] ?? 'putih';

            if ($siswa->current_belt_level !== $normalized) {
                $old = $siswa->current_belt_level;
                $siswa->current_belt_level = $normalized;
                $siswa->save();

                $countUpdated++;

                // Log ke file laravel.log
                Log::info("Sabuk siswa dinormalisasi", [
                    'siswa_id'   => $siswa->id,
                    'nama'       => $siswa->nama_lengkap,
                    'sabuk_lama' => $old,
                    'sabuk_baru' => $normalized,
                ]);

                // Tampilkan di console artisan
                $this->command->warn("🔄 {$siswa->nama_lengkap}: '{$old}' → '{$normalized}'");
            }
        }

        $this->command->info("✅ Normalisasi sabuk selesai. Total diperbarui: {$countUpdated}");
    }
}
