<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Hanya Super Admin yang bisa lihat daftar user.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Hanya Super Admin yang bisa melihat detail user.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Hanya Super Admin yang bisa membuat user baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Hanya Super Admin yang bisa update user.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Hanya Super Admin yang bisa hapus user.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }
}
