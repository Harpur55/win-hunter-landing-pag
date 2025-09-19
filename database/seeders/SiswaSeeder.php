<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Unit;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); 

        $nisPrefix     = 'WH-WH-';
        $paddingLength = 4;

        // Daftar sabuk
        $sabuks = [
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

        // Ambil semua unit
        $units = Unit::all();

        // Cari NIS terakhir sekali saja
        $lastSiswa = Siswa::where('nis', 'like', $nisPrefix . '%')
            ->orderByRaw('CAST(SUBSTRING(nis, ' . (strlen($nisPrefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();

        $startNumber = $lastSiswa ? ((int) substr($lastSiswa->nis, strlen($nisPrefix)) + 1) : 1;

        foreach ($units as $unit) {
            $gender    = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $birthDate = $faker->dateTimeBetween('-20 years', '-5 years');
            $joinDate  = $faker->dateTimeBetween('-5 years', 'now');

            $nis = $nisPrefix . str_pad($startNumber++, $paddingLength, '0', STR_PAD_LEFT);

           Siswa::create([
    'no_register'   => 'REG-' . strtoupper(uniqid()),
    'nis'           => $nis,
    'nama_lengkap'  => $faker->name,
    'jenis_kelamin' => $faker->randomElement(['Laki-laki','Perempuan']),
    'tempat_lahir'  => $faker->city,
    'tanggal_lahir' => $faker->date('Y-m-d'),
    'golongan_darah'=> $faker->randomElement(['A','B','AB','O']),
    'image'         => null,
    'alamat_lengkap'=> $faker->address,
    'no_telepon'    => $faker->phoneNumber,
    'nama_ayah'     => $faker->name('male'),
    'pekerjaan_ayah'=> $faker->jobTitle,
    'nama_ibu'      => $faker->name('female'),
    'pekerjaan_ibu' => $faker->jobTitle,
    'units_id'      => $unit->id,
    'kelas_id'      => $faker->randomElement(['1','2','3','4']),
    'beladiri_yang_pernah_diikuti' => $faker->randomElement(['Silat','Karate','Judo',null]),
    'current_belt_level' => $faker->randomElement($sabuks),
    'joint_date'    => $faker->date('Y-m-d'),
    'status'        => $faker->randomElement(['Aktif','Tidak Aktif']),
]);


            $this->command->info("✅ 1 siswa berhasil dibuat untuk unit: {$unit->name}");
        }
    }
}
