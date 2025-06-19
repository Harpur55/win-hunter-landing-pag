<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachController;



Route::get('/', [\App\Http\Controllers\CoachController::class, 'show'])->name('coach.home');
Route::get('/index', [\App\Http\Controllers\CoachController::class, 'show'])->name('coach.index');
