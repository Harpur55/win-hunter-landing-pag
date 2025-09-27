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

        if (! $user) {
            return redirect()->route('login'); // kalau belum login
        }

        // Cek role dengan Spatie Permission
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return $next($request); // admin & super-admin boleh lanjut
        }

        // kalau role = siswa atau lainnya
        return redirect()->route('home') // ganti sesuai halaman utama aplikasi
            ->with('error', '❌ Maaf, kamu tidak punya akses masuk.');
    }
}
