<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kejuaraan;
use App\Models\Siswa;

class KejuaraanSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kejuaraan = Kejuaraan::first(); // ambil kejuaraan pertama
        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            $kejuaraan->siswa()->attach($siswa->id, [
                'nama_lengkap' => $siswa->nama_lengkap,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tanggal_lahir' => $siswa->tanggal_lahir,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'sabuk' => $siswa->sabuk,
                'kategori_pertandingan' => fake()->randomElement(['kyorugi','poomsae']),
                'tageuk' => fake()->randomElement(['1','2','3','4','5','6','7','8']),
                'kategori_atlit' => fake()->randomElement(['pracadet','cadet','junior','senior']),
                'berat_badan' => fake()->numberBetween(25, 70),
                'tinggi_badan' => fake()->numberBetween(120, 180),
                'medali' => fake()->randomElement(['tidak_ada','emas','perak','perunggu']),
            ]);
        }
    }
}
