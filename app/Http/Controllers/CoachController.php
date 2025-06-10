<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {

        return view('index');
    }
    public function show(){


        $coaches = \App\Models\Coach::paginate(10);
        return view('index', compact('coaches'));
    }

}
