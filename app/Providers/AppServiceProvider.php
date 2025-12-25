<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kejuaraan;
use App\Observers\KejuaraanObserver;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;

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
                User::observe(UserObserver::class);

                Blade::directive('for', function ($expression) {
            return "<?php for{$expression}: ?>";
        });
        
        Blade::directive('endfor', function () {
            return '<?php endfor; ?>';
        });


        //
    }
}
