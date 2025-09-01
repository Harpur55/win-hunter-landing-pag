<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;






Route::get('/', [LandingPageController::class, 'show'])->name('landing-page');




