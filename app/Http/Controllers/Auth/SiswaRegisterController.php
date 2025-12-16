<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SiswaRegisterController extends Controller
{
    /**
     * Tampilkan form register siswa
     */
    public function showRegisterForm()
    {
        return view('filament.siswa.auth.register');
    }

    /**
     * Proses simpan data register siswa
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6|confirmed',
        ]);

        // Buat user baru (default masuk wizard)
        $user = User::create([
            'name'          => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'needs_wizard'  => true, // wajib wizard pertama kali
        ]);

        // login otomatis
        Auth::login($user);

        // arahkan langsung ke wizard siswa, bukan dashboard
        return redirect()->route('wizard.siswa.start')
            ->with('success', 'Registrasi berhasil, silakan lengkapi data!');
    }
}
