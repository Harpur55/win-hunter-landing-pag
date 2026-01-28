<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\Auth\SiswaForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DaftarUjianController;
use App\Http\Controllers\KejuaraanController;
use App\Http\Controllers\SiswaController;
use App\Models\Sertifikat;



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
Route::get('/test-eror', function () {
    return view('ujian.layouts.error.ujian-berakhir', [
        'eventUjian' => (object) [
            'nama_ujian' => 'Ujian Kenaikan Tingkat',
            'tanggal_ujian' => now()->subDay()->toDateString(),
        ],
    ]);
})->name('test-eror');



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


Route::get('/ujian/daftar/{slug}', [DaftarUjianController::class, 'create'])
    ->name('ujian.daftar');

Route::post('/ujian/daftar/{slug}', [DaftarUjianController::class, 'store'])
    ->name('ujian.daftar.store');

    // Kejuaraan Routes
Route::controller(KejuaraanController::class)->group(function () {
    Route::get('/kejuaraan/daftar/{slug}/', 'daftar')
        ->name('kejuaraan.daftar');

    Route::post('/kejuaraan/aftar/{slug}/', 'store')
        ->name('kejuaraan.daftar.store');
});

Route::get('/sertifikat-kejuaraan/{sertifikat}/download', function (
    Sertifikat $sertifikat
) {
    abort_if(
        auth()->user()->siswa->id !== $sertifikat->kejuaraanSiswa->siswa_id,
        403
    );

    return response()->download(
        storage_path('app/public/' . $sertifikat->file)
    );
})->name('sertifikat.kejuaraan.download');



