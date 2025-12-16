<?php

namespace App\Filament\Siswa\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $view = 'filament.siswa.auth.login';

    /**
     * Redirect setelah login → cek wizard di sini.
     */
    protected function getRedirectUrl(): string
    {
        $user = auth()->user();

        // Jika user baru & belum isi wizard → arahkan ke wizard
        if ($user && $user->needs_wizard) {
            return route('filament.siswa.pages.siswa-wizard');
        }

        // Jika wizard sudah selesai → ke dashboard default Filament
        return parent::getRedirectUrl();
    }

    /**
     * Cloudflare Turnstile Captcha (opsional)
     */
    protected function validateCaptcha(): void
    {
        $token = request()->input('cf-turnstile-response');

        if (!$token) {
            throw ValidationException::withMessages([
                'captcha' => 'Silakan centang verifikasi "Saya bukan robot".',
            ]);
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();

        if (!$response->successful() || empty($data['success'])) {
            throw ValidationException::withMessages([
                'captcha' => 'Verifikasi captcha gagal. Coba lagi.',
            ]);
        }
    }
}
