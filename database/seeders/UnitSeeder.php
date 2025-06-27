<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('units')->insert([
        
        [
            'image' =>'assets/images/download.jpg',
            'name' => 'Waterland Metland Transyogi,Cileungsi',
            'link' => 'https://g.co/kgs/p1Fy5WH',
            'description' => 'Pusat latihan Taekwondo Win-hunter.',
            'alamat' =>'Jl. Metland Transyogi, Cileungsi, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820'
        ],
        [
            'image' => 'assets/images/Al-Azhar.png',
            'name' => 'Al-Azhar Syifa Budi Cibubur',
            'link' => 'https://alazharsyifabudi-cibubur.sch.id/',
            'description' => '',
            'alamat' => 'Jl. Raya Alternatif Cibubur, Cileungsi KM. 6.00 Nagrak, Gunung Putri Bogor, Nagrak, Kec. Gn. Putri, Bogor, Jawa Barat 16967'
        ],
       
        [
            'image' => 'assets/images/Al-Azhar.png',
            'name' => 'Al-Azhar BSD Metland Cileungsi',
            'link' => 'https://metland.alazhar-bsd.sch.id/',
            'description' => '',
            'alamat' =>'Jl. Boulevard Metland Cileungsi Sektor 4, Cipenjo, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16320'
        ],
        [
            'image' => 'assets/images/mi-rumah-anak.PNG',
            'name' => 'MI RUMAH ANAK ',
            'link' => 'https://www.mirumahanak.sch.id/',
            'description' => '',
            'alamat' =>'Jl. Pesona Amerika, Nagrak, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16968'
           
            
        ],
        [
            'image' => 'assets/images/Asyahid.jpg',
            'name' => 'SMP Quran Asy Syahid gunung putri ',
            'link' => 'https://www.syahid.com/',
            'description' => '',
            'alamat' => ' Raya Ciangsana, Pabuaran Wetan, RT.001/RW.039, Ciangsana, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16968'
            
        ],
        [
            'image' => 'assets/images/SDIT CAHAYA SUNAH.jpg',
            'name' => 'SDIT Cahaya Sunah cilengsi', 
            'link' => 'https://cahayasunnah.sch.id/',
            'description' => '',
            'alamat' =>'Jl. Alternatif Cibubur No.74, Cileungsi, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820'
           
        ],
        [
            'image' => 'assets/images/RC.jpg',
            'name' => 'Regina Caeli School', 
            'link' => 'https://reginacaelischool.sch.id/',
            'description' => '',
            'alamat' => 'Metland Transyogi, Jl. Trans Yogie No.km.1, Cileungsi Kidul, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820'
           
        ],
       

        ]);
    }
}
