<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Contoh Gate tambahan
         * Bisa dipanggil dengan Gate::allows('manage-users')
         */
        Gate::define('manage-users', function (User $user) {
            return $user->role === 2; // hanya Super Admin
        });

        Gate::define('is-admin', function (User $user) {
            return $user->role === 1;
        });

        Gate::define('is-siswa', function (User $user) {
            return $user->role === 0;
        });
    }
}
