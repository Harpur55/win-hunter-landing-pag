<?php

namespace App\Filament\Siswa\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    protected static string $view = 'filament.siswa.auth.login';

    /**
    //  * Override method authenticate() agar kompatibel dengan Filament v3
    //  */
    // public function authenticate(): ?LoginResponse
    // {
    //     $this->validateCaptcha();

    //     // Lanjutkan autentikasi bawaan Filament
    //     return parent::authenticate();
    // }

    // /**
    //  * Validasi Cloudflare Turnstile Captcha
    //  */
    // protected function validateCaptcha(): void
    // {
    //     $token = request()->input('cf-turnstile-response');

    //     if (!$token) {
    //         throw ValidationException::withMessages([
    //             'captcha' => 'Silakan centang verifikasi "Saya bukan robot".',
    //         ]);
    //     }

    //     $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
    //         'secret' => config('services.turnstile.secret'),
    //         'response' => $token,
    //         'remoteip' => request()->ip(),
    //     ]);

    //     $data = $response->json();

    //     // Gagal koneksi atau verifikasi
    //     if (!$response->successful() || empty($data['success'])) {
    //         throw ValidationException::withMessages([
    //             'captcha' => 'Verifikasi captcha gagal. Coba lagi.',
    //         ]);
    //     }

    //     // Pastikan hostname sesuai (keamanan tambahan)
    //     if (($data['hostname'] ?? '') !== request()->getHost()) {
    //         throw ValidationException::withMessages([
    //             'captcha' => 'Domain tidak valid untuk captcha ini.',
    //         ]);
    //     }

    //     // Opsi tambahan: jika Cloudflare mengembalikan "score" (untuk mode managed)
    //     if (isset($data['score']) && $data['score'] < 0.5) {
    //         throw ValidationException::withMessages([
    //             'captcha' => 'Aktivitas mencurigakan terdeteksi. Silakan coba lagi nanti.',
    //         ]);
    //     }
    // }
}
