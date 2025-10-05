<?php

namespace App\Filament\Siswa\Pages\Auth;

use Filament\Pages\Page;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgetPassword extends Page
{
    protected static ?string $title = 'Lupa Password';

    // View Blade
    protected static string $view = 'filament.siswa.pages.auth.forget-password';

    // Route langsung tanpa web.php
    public static function getRoute(): string
    {
        return '/siswa/forget-password';
    }

    // Properties untuk Livewire
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $step = 1; // 1 = input email, 2 = reset password
    public $message = '';

    /**
     * Step 1: Cek email
     */
    public function checkEmail()
    {
        $user = User::where('email', $this->email)->first();

        if ($user) {
            $this->step = 2; // lanjut ke form password baru
            $this->message = '';
        } else {
            $this->message = 'Email tidak terdaftar.';
        }
    }

    /**
     * Step 2: Reset password
     */
    public function resetPassword()
    {
        // Validasi password
        if (!$this->password || !$this->password_confirmation) {
            $this->message = 'Silakan isi semua kolom password.';
            return;
        }

        if ($this->password !== $this->password_confirmation) {
            $this->message = 'Password dan konfirmasi password tidak sama.';
            return;
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            $user->password = Hash::make($this->password);
            $user->save();

            $this->message = 'Password berhasil diubah. Silakan login dengan password baru.';
            
            // Reset form
            $this->step = 1;
            $this->email = '';
            $this->password = '';
            $this->password_confirmation = '';
        } else {
            $this->message = 'Terjadi kesalahan. Email tidak ditemukan.';
        }
    }
}
