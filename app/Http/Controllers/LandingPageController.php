<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Unit;
use App\Models\Coach;
use App\Models\Kelas;

class LandingPageController extends Controller
{
    public function index()
    {
        
        $galleries = Gallery::query()
            ->where('status', 'aktif')
            ->latest()
            ->get();

        // Tidak pakai status karena tidak ada kolomnya
        $units = Unit::all();
        $kelas = Kelas::all();

        $coaches = Coach::with('documents')->get();

        return view('pages.home', compact(
            'galleries',
            'units',
            'coaches',
            'kelas'
        ));
    }
}