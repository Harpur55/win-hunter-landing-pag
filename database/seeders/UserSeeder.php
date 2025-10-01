<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role sudah ada
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin']);
        $siswaRole      = Role::firstOrCreate(['name' => 'siswa']);

        // Buat super admin default
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // ganti password
            ]
        );

        // Assign role ke user
        if (! $user->hasRole('super-admin')) {
            $user->assignRole($superAdminRole);
        }

        // Contoh buat admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Contoh buat siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@example.com'],
            [
                'name'     => 'Siswa',
                'password' => bcrypt('iniakunsiswa'),
            ]
        );

        if (! $siswa->hasRole('siswa')) {
            $siswa->assignRole($siswaRole);
        }
    }
}
