<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Dashboard;
use App\Filament\Siswa\Widgets\WelcomeWidgetSiswa;
use App\Filament\Siswa\Widgets\SiswaDashboardStats;

class DashboardSiswa extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard Siswa';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }


}
