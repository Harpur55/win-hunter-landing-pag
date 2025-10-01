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
        // Sesuaikan dengan path yang benar
        return view('filament.siswa.auth.register');
    }

    /**
     * Proses simpan data register siswa
     */
    public function register(Request $request)
    {
        // validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6|confirmed',
        ]);

        // simpan ke tabel users
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // login otomatis setelah register
        Auth::login($user);

        // redirect ke dashboard siswa
        return redirect()->route('filament.siswa.pages.dashboard')
            ->with('success', 'Registrasi berhasil, selamat datang!');
    }
}
