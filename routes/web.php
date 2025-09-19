<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;






Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');

Route::get('/force-403', fn() => abort(403));
Route::get('/force-404', fn() => abort(404));
Route::get('/force-500', fn() => abort(500));


Route::get('/check-key', function () {
    return [
        'env' => App::environment(),
        'app_key_exists' => !empty(env('APP_KEY')),
        'config_key_exists' => !empty(Config::get('app.key')),
        'app_key' => env('APP_KEY'), // ⚠️ jangan tampilkan ini di production!
    ];
});


Route::get('/test-log', function () {
    Log::info('✅ Laravel log berjalan normal');
    Log::error('❌ Laravel error log juga jalan');
    return 'Cek storage/logs/laravel.log atau laravel-YYYY-MM-DD.log kalau pakai daily';
});



