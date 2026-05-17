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

        if ($user && $user->needs_wizard) {
            return route('filament.siswa.pages.siswa-wizard');
        }

        return parent::getRedirectUrl();
    }

    protected function authenticateUser(): void
    {
        $this->validateCaptcha(); // ⬅️ captcha dicek di sini

        parent::authenticateUser(); // lanjutkan login normal
    }

    /**
     * Cloudflare Turnstile Captcha
     */
    protected function validateCaptcha(): void
    {
        $token = request()->input('cf-turnstile-response');

        if (!$token) {
            throw ValidationException::withMessages([
                'captcha' => 'Silakan verifikasi bahwa Anda bukan robot.',
            ]);
        }

        $response = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret'   => config('services.turnstile.secret'),
                'response' => $token,
                'remoteip' => request()->ip(),
            ]
        );

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            throw ValidationException::withMessages([
                'captcha' => 'Verifikasi captcha gagal. Silakan coba lagi.',
            ]);
        }
    }
}
