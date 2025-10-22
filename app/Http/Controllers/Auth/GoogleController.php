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
            // 🔹 Gunakan stateful login (normal)
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // 🔹 Gunakan stateless jika sesi tidak cocok
            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        // 🔹 Buat atau update data user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        // 🔹 Tambahkan role siswa jika belum ada
        $role = Role::firstOrCreate(['name' => 'siswa']);
        if (!$user->hasRole('siswa')) {
            $user->assignRole($role);
        }

        // 🔹 Cek apakah user sudah punya data siswa
        $siswa = $user->siswa;

        if (!$siswa) {
            $siswa = Siswa::where('nama_lengkap', 'LIKE', $user->name)->first();

            if ($siswa) {
                $siswa->update([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } else {
                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $user->name,
                    'jenis_kelamin' => 'Laki-laki',
                    'email' => $user->email,
                    'nomor_register' => null,
                    'current_belt_level' => 'Putih',
                    'status' => 'Aktif',
                ]);
            }
        }

        // 🔹 Login menggunakan guard siswa
        Auth::guard('siswa')->login($user);

        // 🔹 Redirect ke dashboard siswa
return redirect()->to(url('/siswa'));
    }
}
