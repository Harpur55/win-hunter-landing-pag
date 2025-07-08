<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('kelas')->insert([
            [
                'image' => 'assets/images/new-logo-win-hunter.png',
                'name' => 'REGULER',
                'description' => 
                    "- Latihan rutin 2x Per Pekan\n" .
                    "- Kyorugi Iuran 275k/Bln\n" .
                    "- Pomsae Iuran 300k/Bln\n" .
                    "- 15 - 30 Siswa/Kelas"
            ],
            [
                'image' => 'assets/images/new-logo-win-hunter.png',
                'name' => 'KHUSUS KYORUGI/ POMSAE',
                'description' => 
                    "- Latihan rutin 2x Per Pekan\n" .
                    "- Iuran 550k/Bln\n" .
                    "- 10 - 20 Siswa/Kelas\n" .
                    "- Free 2x Kejuaraan/ Tahun\n" .
                    "- Free TryOut 1x\n" .
                    "- Free TC (Latihan Persiapan Kejuaraan)"
            ],
            [
                'image' => 'assets/images/new-logo-win-hunter.png',
                'name' => 'PRESTASI',
                'description' => 
                    "- Latihan rutin 4x Per Pekan\n" .
                    "- Biaya Pelatihan 5,5Jt/Tahun (Max. Termin 3 Bulan)\n" .
                    "- Terprogram dan Fokus\n" .
                    "- Free Jaket/ Dobok Khusus di tahun pertama\n" .
                    "- Iuran 275k/Bln\n" .
                    "- 5 - 10 Siswa/Kelas\n" .
                    "- Free 3x Kejuaraan/ Tahun\n" .
                    "- Free TryOut 2x\n" .
                    "- Free TC (Latihan Persiapan Kejuaraan)\n" .
                    "- Discount Basic 10% Biaya Kejuaraan Luar Negeri"
            ],
            [
                'image' => 'assets/images/new-logo-win-hunter.png',
                'name' => 'PRIVATE',
                'description' => "By Call"
            ],
        ]);
    }
    }

