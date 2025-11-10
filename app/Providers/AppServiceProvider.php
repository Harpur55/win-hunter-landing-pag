<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kejuaraan;
use App\Observers\KejuaraanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            Kejuaraan::observe(KejuaraanObserver::class);

        //
    }
}
