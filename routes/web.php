<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\Auth\SiswaForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;


Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');

Route::get('/force-403', fn() => abort(403));
Route::get('/force-404', fn() => abort(404));
Route::get('/force-500', fn() => abort(500));

//setup siswa auth reset password
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


Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
