<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// use Faker\Factory as Faker;

class CoachSeeder extends Seeder
{
   public function run()
{
    // $faker = Faker\Factory::create();

    // $images = [
    //     'assets/images/download.jpg',
    //     'assets/images/sanim.png',
    // ];

    DB::table('coach')->insert([
        
        [
            'foto' =>'assets/images/team/sabeumsyamsul.png',
            'nama' => 'Sabeumnim Syamsul Aripin',
            'sabuk' => 'DAN 5',
            'role' => 'Pelatih Utama dan Owner'
        ],
        [
            'foto' => 'assets/images/team/bayu.png',
            'nama' => 'Laras Bayu Dwi Anggoro',
            'sabuk' => 'DAN 3',
            'role' => 'Pelatih'
        ],
       
        [
            'foto' =>'assets/images/team/sabeumsyamsul.png',
            'nama' => 'Sabeum M. Kurniawan',
            'sabuk' => 'DAN 3',
            'role' => 'Pelatih'
        ],
        [
            'foto' => 'assets/images/team/akmal.png',
            'nama' => 'Sabeum Akmal Taufiqul Hakim',
            'sabuk' => 'DAN 2',
            'role' => 'Pelatih'
            
        ],
        [
            'foto' => 'assets/images/team/fachry.png',
            'nama' => 'Sabeum  M.Fachry ichsan R ',
            'sabuk' => 'DAN 2',
            'role' => 'Pelatih'
        ],
        [
            'foto' => 'assets/images/team/faisal.png',
            'nama' => 'Sabeum  Faisal Abdul Hakim', 
            'sabuk' => 'DAN 1',
            'role' => 'Pelatih'
        ],
        [
            'foto' => 'assets/images/team/hari.png',
            'nama' => 'Sabeum Hari Purnomo', 
            'sabuk' => 'DAN 1',
            'role' => 'Pelatih'
        ],
        [
            'foto' =>'assets/images/team/faisal.png' ,
            'nama' => 'Sabeum  Dirgahayu Agustian', 
            'sabuk' => 'DAN 1',
            'role' => 'Pelatih'
        ],
        [
            'foto' => 'assets/images/team/cindy.png',
            'nama' => 'Sabeum Alya Nurhikmah', 
            'sabuk' => 'DAN 1',
            'role' => 'Pelatih'

        ],
        [
            'foto' => 'assets/images/team/cindy.png',
            'nama' => 'Sabeum Cindy Maya Fahira', 
            'sabuk' => 'DAN 1',
            'role' => 'Pelatih'
        ],

        ]);
}

}

