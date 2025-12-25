<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeightClass;

class WeightClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

           WeightClass::truncate();

        $data = [

            /*
            |--------------------------------------------------------------------------
            | CADET (12–14 TAHUN)
            |--------------------------------------------------------------------------
            */

            // CADET PUTRA
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>null,'max_kg'=>33,'label'=>'U-33 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>33.01,'max_kg'=>37,'label'=>'U-37 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>37.01,'max_kg'=>41,'label'=>'U-41 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>41.01,'max_kg'=>45,'label'=>'U-45 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>45.01,'max_kg'=>49,'label'=>'U-49 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>49.01,'max_kg'=>53,'label'=>'U-53 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>53.01,'max_kg'=>57,'label'=>'U-57 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>57.01,'max_kg'=>61,'label'=>'U-61 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>61.01,'max_kg'=>65,'label'=>'U-65 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'L','min_kg'=>65.01,'max_kg'=>null,'label'=>'+65 kg'],

            // CADET PUTRI
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>null,'max_kg'=>29,'label'=>'U-29 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>29.01,'max_kg'=>33,'label'=>'U-33 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>33.01,'max_kg'=>37,'label'=>'U-37 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>37.01,'max_kg'=>41,'label'=>'U-41 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>41.01,'max_kg'=>44,'label'=>'U-44 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>44.01,'max_kg'=>47,'label'=>'U-47 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>47.01,'max_kg'=>51,'label'=>'U-51 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>51.01,'max_kg'=>55,'label'=>'U-55 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>55.01,'max_kg'=>59,'label'=>'U-59 kg'],
            ['kategori_atlit'=>'cadet','jenis_kelamin'=>'P','min_kg'=>59.01,'max_kg'=>null,'label'=>'+59 kg'],

            /*
            |--------------------------------------------------------------------------
            | JUNIOR (15–17 TAHUN)
            |--------------------------------------------------------------------------
            */

            // JUNIOR PUTRA
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>null,'max_kg'=>45,'label'=>'U-45 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>45.01,'max_kg'=>48,'label'=>'U-48 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>48.01,'max_kg'=>51,'label'=>'U-51 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>51.01,'max_kg'=>55,'label'=>'U-55 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>55.01,'max_kg'=>59,'label'=>'U-59 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>59.01,'max_kg'=>63,'label'=>'U-63 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>63.01,'max_kg'=>68,'label'=>'U-68 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>68.01,'max_kg'=>73,'label'=>'U-73 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>73.01,'max_kg'=>78,'label'=>'U-78 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'L','min_kg'=>78.01,'max_kg'=>null,'label'=>'+78 kg'],

            // JUNIOR PUTRI
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>null,'max_kg'=>42,'label'=>'U-42 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>42.01,'max_kg'=>44,'label'=>'U-44 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>44.01,'max_kg'=>46,'label'=>'U-46 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>46.01,'max_kg'=>49,'label'=>'U-49 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>49.01,'max_kg'=>52,'label'=>'U-52 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>52.01,'max_kg'=>55,'label'=>'U-55 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>55.01,'max_kg'=>59,'label'=>'U-59 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>59.01,'max_kg'=>63,'label'=>'U-63 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>63.01,'max_kg'=>68,'label'=>'U-68 kg'],
            ['kategori_atlit'=>'junior','jenis_kelamin'=>'P','min_kg'=>68.01,'max_kg'=>null,'label'=>'+68 kg'],

            /*
            |--------------------------------------------------------------------------
            | SENIOR (18+ TAHUN)
            |--------------------------------------------------------------------------
            */

            // SENIOR PUTRA
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>null,'max_kg'=>54,'label'=>'U-54 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>54.01,'max_kg'=>58,'label'=>'U-58 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>58.01,'max_kg'=>63,'label'=>'U-63 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>63.01,'max_kg'=>68,'label'=>'U-68 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>68.01,'max_kg'=>74,'label'=>'U-74 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>74.01,'max_kg'=>80,'label'=>'U-80 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>80.01,'max_kg'=>87,'label'=>'U-87 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'L','min_kg'=>87.01,'max_kg'=>null,'label'=>'+87 kg'],

            // SENIOR PUTRI
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>null,'max_kg'=>46,'label'=>'U-46 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>46.01,'max_kg'=>49,'label'=>'U-49 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>49.01,'max_kg'=>53,'label'=>'U-53 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>53.01,'max_kg'=>57,'label'=>'U-57 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>57.01,'max_kg'=>62,'label'=>'U-62 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>62.01,'max_kg'=>67,'label'=>'U-67 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>67.01,'max_kg'=>73,'label'=>'U-73 kg'],
            ['kategori_atlit'=>'senior','jenis_kelamin'=>'P','min_kg'=>73.01,'max_kg'=>null,'label'=>'+73 kg'],
        ];

        WeightClass::insert($data);
    }
    
}
