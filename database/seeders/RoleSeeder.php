<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // contoh assign ke user pertama
        $user = User::find(1);
        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
