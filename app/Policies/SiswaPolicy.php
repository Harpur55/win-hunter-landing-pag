<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super-admin');
    }

    public function view(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super-admin');
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super-admin');
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('super-admin'); // hapus khusus super-admin
    }
}
