<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Unit;
use App\Models\Coach;
use App\Models\Kelas;


class LandingPageController extends Controller
{
    
    public function show()
{
     $galleries = Gallery::where('status', 'aktif')
    ->latest()
    ->get();
    $units     = Unit::all();
    $kelas     = Kelas::all();

    // LOAD relasi documents (INI PENTING)
    $coaches = Coach::with('documents')->get();

    return view('index', compact('galleries', 'units', 'coaches', 'kelas'));
}


    
}
