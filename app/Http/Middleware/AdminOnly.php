<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login'); // kalau belum login
        }

        if ($user->role === 0 || $user->role === 2) {
            return $next($request); // admin & superadmin lanjut
        }

        abort(403, '❌ Maaf anda tidak punya akses masuk.'); // siswa ditolak
    }
}
