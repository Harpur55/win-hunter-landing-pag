<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cek', function () {
    return view('cek');
});
Route::get('/cek', [\App\Http\Controllers\CoachController::class, 'show'])->name('cek.show');