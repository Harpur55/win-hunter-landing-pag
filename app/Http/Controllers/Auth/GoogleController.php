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

        // Buat atau update user berdasarkan email, set nama sesuai google
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

        // Jika user belum punya siswa, coba cari siswa dengan nama sama (case-insensitive).
        // Jika ditemukan, hubungkan user ke data siswa lama (JANGAN timpa biodata).
        if (!$user->siswa) {
            $matched = Siswa::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($user->name)])->first();
            if ($matched) {
                $matched->update([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                // set relation supaya $user->siswa langsung tersedia
                $user->setRelation('siswa', $matched);
            }
            // jika tidak ada yang cocok, jangan buat data siswa otomatis di sini
            // (biarkan dibuat saat user menyimpan profil)
        }

        Auth::guard('siswa')->login($user);

        return redirect('/siswa');
    }
}
