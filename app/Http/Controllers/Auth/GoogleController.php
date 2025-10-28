<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        // Buat atau update user berdasarkan email (bukan siswa)
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        // Pastikan role siswa
        $role = Role::firstOrCreate(['name' => 'siswa']);
        if (!$user->hasRole('siswa')) {
            $user->assignRole($role);
        }

        // Cek apakah user sudah punya siswa
        if (!$user->siswa) {
            // Cari siswa berdasarkan nama lengkap (case-insensitive)
            $matchedSiswa = Siswa::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($googleUser->getName())])->first();

            if ($matchedSiswa) {
                // Hubungkan user dengan siswa yang sudah ada
                $matchedSiswa->update([
                    'user_id' => $user->id,
                    'email' => $user->email, // hanya sinkronisasi email
                ]);

                $user->setRelation('siswa', $matchedSiswa);
            }
            // Jika tidak ditemukan, jangan buat data siswa baru.
            // Biarkan user mengisi profil manual nanti.
        }

        Auth::guard('siswa')->login($user);

        return redirect('/siswa.wh');
    }
}
