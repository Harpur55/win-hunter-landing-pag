<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {

        return view('cek');
    }
    public function show(){


        $coaches = \App\Models\Coach::paginate(10);
        return view('cek', compact('coaches'));
    }

}
