<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateSiswa extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // 🔥 PAKSA ke login siswa
        return route('filament.siswa.auth.login');
    }
}
