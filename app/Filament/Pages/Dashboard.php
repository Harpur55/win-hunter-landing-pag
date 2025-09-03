<?php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    // Ubah label di sidebar
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Admin Win Hunter';

    protected static ?int $navigationSort = -1;

    public function getHeading(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        $user = Auth::user();
        return 'Selamat datang, ' . ($user?->name ?? 'Pengguna');
    }
}




