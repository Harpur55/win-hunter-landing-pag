<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserObserver
{
    public function updated(User $user)
    {
        Log::info("[UserObserver] Event updated() dipanggil.", [
            'user_id' => $user->id,
            'changes' => $user->getChanges(),
        ]);

        if (!request()?->isMethod('POST') && !request()?->isMethod('PUT') && !request()?->isMethod('PATCH')) {
            Log::info("[UserObserver] Batal: request bukan HTTP valid.");
            return;
        }

        if ($user->siswa) {
            Log::info("[UserObserver] Batal: User sudah punya siswa.", [
                'user_id' => $user->id,
            ]);
            return;
        }

        if (!$user->wasChanged()) {
            Log::info("[UserObserver] Batal: Tidak ada perubahan pada user.");
            return;
        }

        $noRegister   = request()->input('no_register');
        $tanggalLahir = request()->input('tanggal_lahir');

        if (!filled($noRegister) || !filled($tanggalLahir)) {
            Log::info("[UserObserver] Batal: Field no_register/tanggal_lahir tidak dikirim.");
            return;
        }

        $nama  = $user->name;
        $email = $user->email;

        Log::info("[UserObserver] Mencari siswa yang cocok...", [
            'no_register' => $noRegister,
            'nama' => $nama,
            'tanggal_lahir' => $tanggalLahir,
        ]);

        $match = Siswa::where('no_register', $noRegister)
            ->whereRaw('LOWER(nama_lengkap) = ?', [Str::lower($nama)])
            ->where('tanggal_lahir', $tanggalLahir)
            ->first();

        if ($match) {
            Log::info("[UserObserver] Match ditemukan → menghubungkan user ke siswa.", [
                'user_id' => $user->id,
                'siswa_id' => $match->id,
            ]);

            $match->update(['user_id' => $user->id]);
            return;
        }

        Log::info("[UserObserver] Tidak ditemukan match → membuat siswa baru.", [
            'user_id' => $user->id,
        ]);

        Siswa::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $nama,
            'email'         => $email,
            'no_register'   => $noRegister,
            'tanggal_lahir' => $tanggalLahir,
        ]);
    }
}
