<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
        'foto' => 'assets/images/team/sabeumsyamsul.png',
        'nama' => 'Sabeumnim Syamsul Aripin',
        'sabuk' => 'DAN 5',
        'nomor_telepon' => '0987654321',
        'alamat' => 'Cielungsi',
        'role' => 'pelatih Utama dan Owner', 
        'status' => 'Active',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),

        ],
        [
             'foto' => 'assets/images/team/bayu.png',
             'nama' => 'Laras Byu D.A',
             'sabuk' => 'DAN 3',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],
       
        [
            'foto' =>'assets/images/team/sabeumsyamsul.png',
            'nama' => 'Sabeum M.Kurniawan',
            'sabuk' => 'DAN 3',
            'nomor_telepon' => '0987654321',
            'alamat' => 'Cielungsi',
            'role' => 'pelatih', // sesuaikan dengan nilai yang dibutuhkan
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'foto' => 'assets/images/team/akmal.png',
            'nama' => 'Sabeum Akmal ',
            'sabuk' => 'DAN 2',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
            
        ],
        [
            'foto' => 'assets/images/team/fachry.png',
           'nama' => 'Sabeum Fachry ',
             'sabuk' => 'DAN 2',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],
        [
            'foto' => 'assets/images/team/faisal.png',
             'nama' => 'Sabeum Faisal ',
             'sabuk' => 'DAN 1',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],
        [
            'foto' => 'assets/images/team/Hari.png',
             'nama' => 'Sabeum Hari',
             'sabuk' => 'DAN 1',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],
        [
            'foto' =>'assets/images/team/faisal.png' ,
             'nama' => 'Sabeum Dirga',
             'sabuk' => 'DAN 1',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],
        [
            'foto' => 'assets/images/team/cindy.png',
             'nama' => 'Sabeum Alya',
             'sabuk' => 'DAN 1',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),

        ],
        [
            'foto' => 'assets/images/team/cindy.png',
            'nama' => 'Sabeum Cindy Maya Fachira',
             'sabuk' => 'DAN 1',
             'nomor_telepon' => '0987654321',
             'alamat' => 'Cielungsi',
             'role' => 'pelatih', 
             'status' => 'Active',
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
        ],

        ]);
}

}

