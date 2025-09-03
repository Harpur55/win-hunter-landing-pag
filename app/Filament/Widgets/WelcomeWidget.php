<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    // Atur posisi widget di dashboard
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = -1;

    public static function canView(): bool
    {
        return Auth::check(); // hanya tampil kalau user login
    }
}
