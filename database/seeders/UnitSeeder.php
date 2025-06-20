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
        ],
        [
            'image' => 'assets/images/Al-Azhar.png',
            'name' => 'Al-Azhar Syifa Budi Cibubur',
            'link' => 'https://alazharsyifabudi-cibubur.sch.id/',
            'description' => ''
        ],
       
        [
            'image' => 'assets/images/Al-Azhar.png',
            'name' => 'Al-Azhar BSD Metland Cileungsi',
            'link' => 'https://metland.alazhar-bsd.sch.id/',
            'description' => ''
        ],
        [
            'image' => 'assets/images/mi-rumah-anak.PNG',
            'name' => 'Sabeum Akmal Taufiqul Hakim',
            'link' => 'https://www.mirumahanak.sch.id/',
            'description' => '',
           
            
        ],
        [
            'image' => 'assets/images/Asyahid.jpg',
            'name' => 'SMP Quran Asy Syahid gunung putri ',
            'link' => 'https://www.syahid.com/',
            'description' => ''
            
        ],
        [
            'image' => 'assets/images/SDIT CAHAYA SUNAH.jpg',
            'name' => 'SDIT Cahaya Sunah cilengsi', 
            'link' => 'https://cahayasunnah.sch.id/',
            'description' => ''
           
        ],
        [
            'image' => 'assets/images/RC.jpg',
            'name' => 'Regina Caeli School', 
            'link' => 'https://reginacaelischool.sch.id/',
            'description' => '',
           
        ],
       

        ]);
    }
}
