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
use Illuminate\Support\HtmlString;


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


        // Get the next upcoming Kejuaraan
        $upcomingChampionship = Kejuaraan::whereDate('tanggal_mulai', '>=', now()->startOfDay())
            ->withCount('siswa')
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // kalau tidak ada yang upcoming, ambil yang terakhir
        if (!$upcomingChampionship) {
            $upcomingChampionship = Kejuaraan::orderBy('tanggal_mulai', 'desc')
                ->withCount('siswa')
                ->first();
        }

        $championshipName = $upcomingChampionship?->nama_kejuaraan ?? 'Tidak ada';
        $championshipParticipants = $upcomingChampionship?->siswa_count ?? 0;
        $championshipDate = $upcomingChampionship
            ? Carbon::parse($upcomingChampionship->tanggal_mulai)->format('d-m-Y')
            : 'Tidak ada';


        //medali
        $gold = $upcomingChampionship?->siswa->where('pivot.medali', 'emas')->count() ?? 0;
        $silver = $upcomingChampionship?->siswa->where('pivot.medali', 'perak')->count() ?? 0;
        $bronze = $upcomingChampionship?->siswa->where('pivot.medali', 'perunggu')->count() ?? 0;


        //best atlet
        $today = now()->format('m-d'); // format bulan-hari

        if ($today === '12-31') {
            // Hitung Best Atlet
            $bestAtlet = Siswa::withCount('kejuaraan')
                ->with('kejuaraan')
                ->get()
                ->map(function ($siswa) {
                    $siswa->total_emas = $siswa->kejuaraan->where('pivot.medali', 'emas')->count();
                    $siswa->total_perak = $siswa->kejuaraan->where('pivot.medali', 'perak')->count();
                    $siswa->total_perunggu = $siswa->kejuaraan->where('pivot.medali', 'perunggu')->count();
                    return $siswa;
                })
                ->sortByDesc(fn($s) => [$s->kejuaraan_count, $s->total_emas, $s->total_perak, $s->total_perunggu])
                ->first();

            $bestAtletName = $bestAtlet?->nama_lengkap ?? 'Tidak ada';
            $bestAtletKejuaraan = $bestAtlet?->kejuaraan_count ?? 0;
            $bestAtletGold = $bestAtlet?->total_emas ?? 0;
            $bestAtletSilver = $bestAtlet?->total_perak ?? 0;
            $bestAtletBronze = $bestAtlet?->total_perunggu ?? 0;

            $bestAtletDescription = new HtmlString("
        🏆 Total Kejuaraan: {$bestAtletKejuaraan}<br>
        🥇 Medali: 🥇 {$bestAtletGold} 🥈 {$bestAtletSilver} 🥉 {$bestAtletBronze}
    ");
        } else {
            $bestAtletName = 'Menunggu Hasil';
            $bestAtletDescription = new HtmlString("Hasil akan diumumkan pada 31 Desember");
        }


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

            Stat::make('Kejuaraan Akan Datang', strtoupper($championshipName))
                ->description(new HtmlString("
                 Jumlah Peserta : {$championshipParticipants}<br>
                 📅 Tanggal : {$championshipDate}<br>
                 🥇 Medali : 🥇 {$gold} 🥈 {$silver} 🥉 {$bronze}
                "))
                ->color('info')
                ->extraAttributes(['class' => '']),

            Stat::make('Best Atlet', strtoupper($bestAtletName))
                ->description($bestAtletDescription)
                ->color('primary')
                ->extraAttributes(['class' => ''])

        ];
    }
}
