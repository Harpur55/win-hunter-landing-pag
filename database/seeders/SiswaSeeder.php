<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Unit;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
      $faker = Faker::create('id_ID'); // Menggunakan Faker dengan lokal Indonesia
          $nisPrefix = 'WH-WH-';
        $paddingLength = 4; // Panjang angka di belakang prefix, contoh 0001 (4 digit)

        // Cari NIS tertinggi yang sudah ada dengan prefix yang sama
        $lastSiswa = Siswa::where('nis', 'like', $nisPrefix . '%')
                              ->orderByRaw('CAST(SUBSTRING(nis, ' . (strlen($nisPrefix) + 1) . ') AS UNSIGNED) DESC')
                              ->first();

        $startNumber = 1; // Default jika belum ada NIS dengan prefix ini
        if ($lastSiswa) {
            // Ekstrak bagian angka dari NIS terakhir
            $lastNisNumber = (int) substr($lastSiswa->nis, strlen($nisPrefix));
            $startNumber = $lastNisNumber + 1; // Lanjutkan dari angka berikutnya
        }
        for ($i = 0; $i < 20; $i++) {
             $currentNisNumber = $startNumber + $i; // Angka NIS untuk data saat ini
            
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $birthDate = $faker->dateTimeBetween('-40 years', '-5 years'); // Umur 10-20 tahun
            $joinDate = $faker->dateTimeBetween('-5 years', 'now');
             // Bergabung 5 tahun terakhir

            // Bentuk NIS dengan prefix dan angka berurutan, ditambahkan padding
            $nis = $nisPrefix . str_pad($currentNisNumber, $paddingLength, '0', STR_PAD_LEFT);

            Siswa::create([
                'no_register' => 'REG-' . $faker->unique()->randomNumber(5), // Nomor registrasi unik
                'nis' => $nis,
                'nama_lengkap' => ($gender === 'Laki-laki' ? $faker->name('male') : $faker->name('female')),
                'jenis_kelamin' => $gender,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $birthDate->format('Y-m-d'),
                'golongan_darah' => $faker->randomElement(['A', 'B', 'AB', 'O', null]), // Boleh kosong
                'image' => null, // Biarkan null atau gunakan URL placeholder jika diperlukan
                'alamat_lengkap' => $faker->address,
                'no_telepon' => $faker->phoneNumber,
                'nama_ayah' => $faker->name('male'),
                'pekerjaan_ayah' => $faker->jobTitle,
                'nama_ibu' => $faker->name('female'),
                'pekerjaan_ibu' => $faker->jobTitle,
                 'units_id' => Unit::inRandomOrder()->first()->id,               
              'kelas_id' => $faker->randomElement(['1', '2', '3', '4']),
                'current_belt_level' => $faker->randomElement(['Putih', 'Kuning', 'Kuning Strip Hijau','Hijau','Hijau strip Biru','Biru','Biru strip Merah','Merah strip hitam','Hitam']),
                // 'next_belt_level' => '',
                'joint_date' => $joinDate->format('Y-m-d'),
                'status' => $faker->randomElement(['Aktif', 'Tidak Aktif', 'Cuti']),
            ]);
        }

    }
    //  public function uktParticipants(): HasMany
    // {
    //     return $this->hasMany(UktParticipant::class, 'siswa_id');
    // }

}