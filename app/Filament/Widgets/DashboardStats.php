<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\Coach;
use App\Models\Unit;
use App\Models\EventUjian;
use App\Models\Kejuaraan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Filament\Actions\Action;
use Carbon\Carbon;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    public ?string $bestAtletName = 'Menunggu Hasil';
    public ?int $totalEmas = 0;
    public ?int $totalPerak = 0;
    public ?int $totalPerunggu = 0;
    public ?int $totalKejuaraan = 0;

    // Tombol di header (bisa kamu hapus kalau hanya mau tombol di box)
    protected function getHeaderActions(): array
    {
        return [
            Action::make('hitung_best_atlet')
                ->label('Hitung Atlet Terbaik')
                ->icon('heroicon-o-trophy')
                ->color('success')
                ->action(fn() => $this->hitungBestAtlet()),
        ];
    }

    // 🔹 Fungsi menghitung atlet terbaik
    public function hitungBestAtlet(): void
    {
        $bestAtlet = Siswa::with('kejuaraan')
            ->get()
            ->map(function ($siswa) {
                $siswa->total_emas = $siswa->kejuaraan->where('pivot.medali', 'emas')->count();
                $siswa->total_perak = $siswa->kejuaraan->where('pivot.medali', 'perak')->count();
                $siswa->total_perunggu = $siswa->kejuaraan->where('pivot.medali', 'perunggu')->count();
                $siswa->total_kejuaraan = $siswa->kejuaraan->count();
                return $siswa;
            })
            ->sortByDesc(fn($s) => [$s->total_emas, $s->total_perak, $s->total_perunggu])
            ->first();

        if ($bestAtlet) {
            $this->bestAtletName = $bestAtlet->nama_lengkap;
            $this->totalEmas = $bestAtlet->total_emas;
            $this->totalPerak = $bestAtlet->total_perak;
            $this->totalPerunggu = $bestAtlet->total_perunggu;
            $this->totalKejuaraan = $bestAtlet->total_kejuaraan;
        } else {
            $this->bestAtletName = 'Belum Ada Data';
            $this->totalEmas = $this->totalPerak = $this->totalPerunggu = $this->totalKejuaraan = 0;
        }
    }

    protected function getStats(): array
    {
        $upcomingEvent = EventUjian::whereDate('tanggal_ujian', '>=', Carbon::today())
            ->withCount('siswa')
            ->orderBy('tanggal_ujian')
            ->first();

        $eventName = $upcomingEvent?->nama_ujian ?? 'Tidak ada';
        $eventParticipants = $upcomingEvent?->siswa_count ?? 0;

        $upcomingChampionship = Kejuaraan::whereDate('tanggal_mulai', '>=', now()->startOfDay())
            ->withCount('siswa')
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

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

        $gold = $upcomingChampionship?->siswa->where('pivot.medali', 'emas')->count() ?? 0;
        $silver = $upcomingChampionship?->siswa->where('pivot.medali', 'perak')->count() ?? 0;
        $bronze = $upcomingChampionship?->siswa->where('pivot.medali', 'perunggu')->count() ?? 0;

        // Deskripsi Best Atlet
        $bestAtletDescription = $this->bestAtletName === 'Menunggu Hasil'
            ? new HtmlString('Klik tombol di bawah untuk menghitung atlet terbaik.<br>
                <button wire:click="hitungBestAtlet" class="px-3 py-1 mt-2 bg-green-600 text-white rounded">
                    🏆 Hitung Atlet Terbaik
                </button>')
            : new HtmlString("
                Jumlah Kejuaraan: <b>{$this->totalKejuaraan}</b><br>
                🥇 Emas: {$this->totalEmas} |
                🥈 Perak: {$this->totalPerak} |
                🥉 Perunggu: {$this->totalPerunggu}<br>
                <button wire:click='hitungBestAtlet' class='px-3 py-1 mt-2 bg-blue-600 text-white rounded'>
                    🔄 Hitung Ulang
                </button>
            ");

        return [

       Stat::make('Jumlah Siswa', Siswa::count())
    ->description('Total siswa terdaftar')
    ->descriptionIcon('heroicon-m-user-group')
    ->color('primary')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-blue-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-blue-50 via-sky-50 to-indigo-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-slate-800

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1
        ',
    ]),

Stat::make('Jumlah Pelatih', Coach::count())
    ->description('Total pelatih aktif')
    ->descriptionIcon('heroicon-m-academic-cap')
    ->color('success')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-emerald-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-emerald-50 via-green-50 to-lime-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-emerald-950/40

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1
        ',
    ]),

Stat::make('Jumlah Unit', Unit::count())
    ->description('Unit latihan terdaftar')
    ->descriptionIcon('heroicon-m-building-office-2')
    ->color('warning')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-amber-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-amber-50 via-yellow-50 to-orange-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-amber-950/30

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1
        ',
    ]),

Stat::make('Event Ujian Akan Datang', $eventName)
    ->description("Jumlah Peserta: {$eventParticipants}")
    ->descriptionIcon('heroicon-m-calendar-days')
    ->color('info')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-cyan-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-cyan-50 via-sky-50 to-blue-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-cyan-950/30

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1
        ',
    ]),

Stat::make('Kejuaraan Akan Datang', strtoupper($championshipName))
    ->description(new HtmlString("
        Jumlah Peserta : {$championshipParticipants}<br>
        📅 {$championshipDate}<br>
        🥇 {$gold} 🥈 {$silver} 🥉 {$bronze}
    "))
    ->descriptionIcon('heroicon-m-trophy')
    ->color('info')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-purple-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-purple-50 via-fuchsia-50 to-pink-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-purple-950/30

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1
        ',
    ]),

Stat::make('Best Atlet', strtoupper($this->bestAtletName))
    ->description($bestAtletDescription)
    ->descriptionIcon('heroicon-m-star')
    ->color('primary')
    ->extraAttributes([
        'class' => '
            rounded-2xl
            border border-rose-200/60 dark:border-slate-800

            bg-gradient-to-br
            from-rose-50 via-pink-50 to-red-100

            dark:from-slate-900
            dark:via-slate-900
            dark:to-rose-950/30

            shadow-sm hover:shadow-xl
            transition-all duration-300
            hover:-translate-y-1

            ring-1 ring-rose-100/50
            dark:ring-rose-900/20
        ',
    ]),
    
        ];
    }
}
