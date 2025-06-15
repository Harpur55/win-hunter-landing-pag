<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\CoachController::class, 'show'])->name('coach.show');

Route::get('/cek', function () {
    return view('cek');
});
Route::get('/index', [\App\Http\Controllers\CoachController::class, 'show'])->name('coach.show');