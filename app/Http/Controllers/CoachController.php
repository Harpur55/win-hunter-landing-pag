<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Unit;

class CoachController extends Controller
{
    public function index()
    {

        return view('index');
    }
    public function show(){


        $coaches = Coach::all();
        $units = Unit::all();
        return view('index', compact('coaches', 'units'));
    }

}
