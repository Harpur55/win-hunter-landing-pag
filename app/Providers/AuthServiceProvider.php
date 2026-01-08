<?php

namespace App\Providers;

use App\Models\Siswa;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\SiswaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class  => UserPolicy::class,
        Siswa::class => SiswaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Gate berbasis Spatie Role
         */
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('super-admin');
        });

        Gate::define('is-admin', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('is-siswa', function (User $user) {
            return $user->hasRole('siswa');
        });

        Gate::define('is-pelatih', function (User $user) {
            return $user->hasRole('pelatih');
        });
    }
}
