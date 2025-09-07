<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Daftar route middleware.
     *
     * Middleware ini bisa dipanggil di route atau panel provider.
     */
    protected $routeMiddleware = [
        'adminOnly' => \App\Http\Middleware\AdminOnly::class,
    ];
}
