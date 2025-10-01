<?php

namespace App\Filament\Widgets;

// use App\Models\Siswa;
// use App\Models\Coach;
// use App\Models\Unit;
// use App\Models\EventUjian;
// use App\Models\Kejuaraan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;


class SiswaDashboardStats extends BaseWidget


{
    protected static ?int $sort = 1;

    protected function getStats(): array

    {
           {
        return [
            Stat::make('Event Ujian Akan Datang', 'Ujian Kenaikan Sabuk')
                ->description('📅 Tanggal: 01-12-2025')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Kejuaraan Akan Datang', 'Kejuaraan Taekwondo Nasional')
                ->description('📅 Tanggal: 15-12-2025')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Jumlah Medali', new HtmlString("🥇 0 | 🥈 0 | 🥉 0"))
                ->description('Total medali yang diperoleh')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
  
}
}