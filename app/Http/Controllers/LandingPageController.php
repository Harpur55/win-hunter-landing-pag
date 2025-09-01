<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Unit;
use App\Models\Coach;


class LandingPageController extends Controller
{
    
    public function show(){
        $galleries = Gallery::all();
        $units = Unit::all();
        $coaches = Coach::all();
        return view('index', compact('galleries','units','coaches'));

    }
}
