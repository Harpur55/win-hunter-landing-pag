<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\Coach;
use App\Models\Unit;
use App\Models\EventUjian;
use App\Models\Kejuaraan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DashboardStats extends BaseWidget


{
    protected static ?int $sort = 1;

    protected function getStats(): array
    
    {
        // Get the next upcoming EventUjian
        
 $upcomingEvent = EventUjian::whereDate('tanggal_ujian', '>=', Carbon::today())
    ->withCount('siswa')
    ->orderBy('tanggal_ujian')
    ->first();

$eventName = $upcomingEvent ? $upcomingEvent->nama_ujian : 'Tidak ada';
$eventParticipants = $upcomingEvent ? $upcomingEvent->siswa_count : 0;

$upcomingChampionship = Kejuaraan::whereDate('tanggal_mulai', '>=', Carbon::today())
    ->orderBy('tanggal_mulai')
    ->first();  

$championshipName = $upcomingChampionship ? $upcomingChampionship->nama_kejuaraan : 'Tidak ada';
$championshipParticipants = $upcomingChampionship ? $upcomingChampionship->jumlah_peserta : 0;
$championshipDate = $upcomingChampionship
    ? Carbon::parse($upcomingChampionship->tanggal_mulai)->format('d-m-Y')
    : 'Tidak ada';


        return [
            Stat::make('Jumlah Siswa', Siswa::count())
                ->description('Total siswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Jumlah Pelatih', Coach::count())
                ->description('Total pelatih aktif')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('Jumlah Unit', Unit::count())
                ->description('Unit latihan terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),

           Stat::make('Event Ujian Akan Datang', $eventName)
                 ->description('Jumlah Peserta: ' . $eventParticipants)
                 ->descriptionIcon('heroicon-m-academic-cap')
                 ->color('info'),

             Stat::make('Kejuaraan Akan Datang', $championshipName)
                  
                 ->description("Tanggal: {$championshipDate} ||  Jumlah Peserta: {$championshipParticipants}")
                    // ->descriptionIcon('heroicon-m-trophy')
                 ->color('secondary'),
        ];
    }
}
