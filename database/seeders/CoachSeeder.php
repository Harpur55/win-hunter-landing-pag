<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CoachSeeder extends Seeder
{
    public function run(){



    $faker = Faker::create();

        $images = [
            'assets/images/download.jpg',
            'assets/images/sanim.png',
        ];

        foreach (range(1, 10) as $index) {
            Coach::create([
                'foto' => $faker->randomElement($images),  // pakai gambar acak dari array
                'nama' => $faker->name,
                'sabuk' => $faker->randomElement(['Putih', 'Kuning', 'Hijau', 'Biru', 'Merah', 'Hitam']),
            ]);
        }
    }
}



