<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role default dengan guard 'web'
        $roles = [
            'super-admin',
            'admin',
            'siswa',
            'pelatih',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web']
            );
        }

        // Contoh assign role ke user pertama
        $user = User::find(1);
        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
