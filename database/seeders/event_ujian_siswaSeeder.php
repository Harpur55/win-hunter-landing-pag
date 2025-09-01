<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class event_ujian_siswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Hapus semua data yang ada di tabel 'event_ujian_siswa'
        $eventUjianIds = DB::table('event_ujian')->pluck('id');

        // Pilihan level sabuk
        $beltLevels = [
            'Putih',
            'Kuning',
            'Kuning Strip Hijau',
            'Hijau',
            'Hijau Strip Biru',
            'Biru',
            'Biru Strip Merah',
            'Merah',
            'Merah Strip Hitam 1',
            'Merah Strip Hitam 2',
            'Hitam',
        ];

        // Keterangan acak
        $keteranganOptions = ['Lulus', 'Tidak Lulus', 'on proses'];

        // ID siswa yang unik
        $siswaIds = range(1, 20);

        foreach ($eventUjianIds as $eventId) {
            // Acak dan ambil 15 ID siswa yang unik untuk setiap event
            shuffle($siswaIds);
            $selectedSiswa = array_slice($siswaIds, 0, 15);

            foreach ($selectedSiswa as $siswaId) {
                // Pilih current_belt_level secara acak
                $currentBeltLevel = $beltLevels[array_rand($beltLevels)];

                // Temukan index dari current_belt_level
                $currentIndex = array_search($currentBeltLevel, $beltLevels);

                // Tentukan next_belt_level
                $nextBeltLevel = null;
                // Pastikan bukan sabuk terakhir (Hitam)
                if ($currentIndex !== false && $currentIndex < count($beltLevels) - 1) {
                    $nextBeltLevel = $beltLevels[$currentIndex + 1];
                }

                // Pilih keterangan acak
                $keterangan = $keteranganOptions[array_rand($keteranganOptions)];

                DB::table('event_ujian_siswa')->insert([
                    'event_ujian_id' => $eventId,
                    'siswa_id' => $siswaId,
                    'current_belt_level' => $currentBeltLevel,
                    'next_belt_level' => $nextBeltLevel,
                    'keterangan' => $keterangan, // Baris ini ditambahkan
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}


