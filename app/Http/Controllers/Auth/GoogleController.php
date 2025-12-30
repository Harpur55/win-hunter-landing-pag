<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
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

        /**
         * 1️⃣ BUAT / UPDATE USER (TANPA SINGGUNG SISWA)
         */
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'       => $googleUser->getName() ?? $googleUser->getEmail(),
                'google_id'  => $googleUser->getId(),
                'avatar'     => $googleUser->getAvatar(),
                'password'   => bcrypt(str()->random(16)),
            ]
        );

        /**
         * 2️⃣ PASTIKAN ROLE SISWA
         */
        $role = Role::firstOrCreate(['name' => 'siswa']);
        if (!$user->hasRole('siswa')) {
            $user->assignRole($role);
        }

        /**
         * ❌ HAPUS TOTAL AUTO-LINK BERDASARKAN NAMA
         * (ini sumber utama NIS tidak cocok)
         */

        /**
         * 3️⃣ LOGIN (TETAP PAKAI GUARD ANDA)
         */
        Auth::guard('siswa')->login($user);

        /**
         * 4️⃣ REDIRECT TETAP KE /siswa
         * Validasi NIS + Nama + Unit dilakukan di halaman /siswa
         */
        return redirect('/siswa');
    }
}
