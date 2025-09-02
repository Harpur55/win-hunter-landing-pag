<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Siswa;
use App\Models\Unit;
use App\Models\Coach;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public function getViewData(): array
    {
        return [
            'siswaCount'  => Siswa::count(),
            'unitCount'   => Unit::count(),
            'coachCount'  => Coach::count(),
        ];
    }
}
