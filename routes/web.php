<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\Auth\SiswaForgotPasswordController;


Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');

Route::get('/force-403', fn() => abort(403));
Route::get('/force-404', fn() => abort(404));
Route::get('/force-500', fn() => abort(500));

//setup siswa auth reset password
Route::prefix('siswa')->name('siswa.')->group(function () {
Route::get('/siswa/forgot-password', [SiswaForgotPasswordController::class, 'showLinkRequestForm'])->name('siswa.password.request');
Route::post('/siswa/forgot-password', [SiswaForgotPasswordController::class, 'sendResetLinkEmail'])->name('siswa.password.email');
Route::get('/siswa/reset-password/{token}', [SiswaForgotPasswordController::class, 'showResetForm'])->name('siswa.password.reset');
Route::post('/siswa/reset-password', [SiswaForgotPasswordController::class, 'reset'])->name('siswa.password.update');

});

