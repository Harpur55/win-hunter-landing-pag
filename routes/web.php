<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\SiswaRegisterController;

Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');

Route::get('/force-403', fn() => abort(403));
Route::get('/force-404', fn() => abort(404));
Route::get('/force-500', fn() => abort(500));


