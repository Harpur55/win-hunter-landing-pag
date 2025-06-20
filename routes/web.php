<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\UnitController;





Route::get('/', [CoachController::class, 'show'])->name('home');

