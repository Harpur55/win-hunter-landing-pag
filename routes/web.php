<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\Auth\SiswaForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DaftarUjianController;
use App\Http\Controllers\SiswaController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');

Route::get('/force-403', fn() => abort(403));
Route::get('/force-404', fn() => abort(404));
Route::get('/force-500', fn() => abort(500));

/*
|--------------------------------------------------------------------------
| Siswa Password Reset
|--------------------------------------------------------------------------
*/
Route::prefix('siswa.wh')->name('siswa.')->group(function () {

    Route::get('/forgot-password', [SiswaForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [SiswaForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [SiswaForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [SiswaForgotPasswordController::class, 'reset'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Google OAuth
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| Sertifikat Page
|--------------------------------------------------------------------------
*/
Route::get('/sertifikat', fn() => view('sertifikat'));

/*
|--------------------------------------------------------------------------
| API GET SISWA (Untuk Auto-Fill)
|--------------------------------------------------------------------------
| WAJIB PAKAI CONTROLLER → Supaya bisa ditambah validasi/logika.
|--------------------------------------------------------------------------
*/

Route::get('/api/siswa/{id}', [SiswaController::class, 'getSiswa'])
    ->name('api.siswa.get');
    

  Route::prefix('siswa')->name('siswa.')->group(function () {

    // Halaman Form Input
    Route::get('/input', [SiswaController::class, 'inputDataSiswa'])
        ->name('input');

    // ✅ SIMPAN / UPDATE
    Route::post('/store', [SiswaController::class, 'storeOrUpdate'])
        ->name('store');

    // 🔍 AUTOFILL
    Route::get('/search', [SiswaController::class, 'searchByName'])
        ->name('search');
});

Route::get('/test-observer', function () {

    // Simulasi request (karena observer butuh POST/PUT/PATCH method)
    request()->setMethod('POST'); 
    request()->merge([
        'no_register'   => 'REG-999',
        'tanggal_lahir' => '2005-02-10',
    ]);

    // 1) buat user baru
    $user = \App\Models\User::create([
        'name' => 'Hari purnomoo',
        'email' => 'harpur@tesmail.com',
        'password' => bcrypt('123456'),
    ]);

    // 2) update user (trigger observer)
    $user->update([
        'name' => 'hari purnomo aji'
    ]);

    return [
        'user'   => $user->fresh(),
        'siswa'  => $user->fresh()->siswa,
        'status' => $user->fresh()->siswa ? 'Observer bekerja!' : 'Observer TIDAK jalan!',
    ];
});
//|--------------------------------------------------------------------------
//| Daftar Ujian (Form & Store)
//|--------------------------------------------------------------------------
//*/
Route::get('/ujian/daftar/{eventId}', [DaftarUjianController::class, 'create'])
    ->name('ujian.daftar');

Route::post('/ujian/daftar/{eventId}', [DaftarUjianController::class, 'store'])
    ->name('ujian.daftar.store');



