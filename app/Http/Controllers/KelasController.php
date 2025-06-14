<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kelas;

class KelasController extends Controller
{
    //

    public function index()
    {
        // Logic to retrieve and return all classes

        $kelas = Kelas::all();
        return view('kelas.index', compact('kelas'));

    }
    public function show($id)
    {
        // Logic to retrieve and return a specific class by ID
    }
}
