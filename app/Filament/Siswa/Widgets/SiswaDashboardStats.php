<?php

namespace App\Filament\Siswa\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SiswaDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
{
    $user = Auth::user();
    $siswa = $user->siswa;

    if (!$siswa) {
        return [
            Stat::make('Data Tidak Ditemukan', 'Silakan lengkapi profil dulu')
                ->description('Profil siswa belum ada')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'bg-red-500 text-white shadow-lg rounded-xl',
                ]),
        ];
    }

    $eventUjian = $siswa->ujian()->orderBy('tanggal_ujian', 'asc')->first();

    // 🔹 Ambil kejuaraan terbaru yang diikuti siswa
    $kejuaraan = $siswa->kejuaraan()
        ->orderBy('tanggal_mulai', 'desc')
        ->first();

    $medali = DB::table('kejuaraan_siswa')
        ->where('siswa_id', $siswa->id)
        ->selectRaw("
            SUM(CASE WHEN medali = 'emas' THEN 1 ELSE 0 END) as emas,
            SUM(CASE WHEN medali = 'perak' THEN 1 ELSE 0 END) as perak,
            SUM(CASE WHEN medali = 'perunggu' THEN 1 ELSE 0 END) as perunggu
        ")
        ->first();

    return [
        Stat::make(
            'Ujian yang Diikuti',
            $eventUjian?->nama_ujian ?? 'Belum ada ujian'
        )
            ->description(
                $eventUjian
                    ? "📅 " . Carbon::parse($eventUjian->tanggal_ujian)->format('d-m-Y') .
                    " | 📍 " . $eventUjian->lokasi_ujian
                    : 'Belum ada jadwal ujian'
            )
            ->color('success')
            ->extraAttributes(['class' => '
                bg-violet-300 
                dark:bg-violet-600
                hover:bg-violet-500
                text-gray-900 
                dark:text-white 
                shadow-lg 
                border-none 
                rounded-xl 
                transition 
                duration-300 
                dark:hover:bg-[##15803d]
            ',]),

        Stat::make(
            'Kejuaraan yang Diikuti',
            $kejuaraan?->nama_kejuaraan ?? 'Belum ada kejuaraan'
        )
            ->description(
                $kejuaraan
                    ? "📅 " . Carbon::parse($kejuaraan->tanggal_mulai)->format('d-m-Y') . " s/d " . Carbon::parse($kejuaraan->tanggal_selesai)->format('d-m-Y')
                    : 'Belum ada jadwal kejuaraan'
            )
            ->descriptionIcon('heroicon-m-trophy')
            ->color('primary')
            ->extraAttributes(['class' => '
                bg-blue-300
                dark:bg-blue-500
                text-gray-900 
                dark:text-white 
                shadow-lg 
                border-none 
                rounded-xl 
                transition 
                duration-300 
            ',]),

        Stat::make(
            'Jumlah Medali',
            new HtmlString("
                <ul class='space-y-1 text-left'>
                    <li>🥇 <span class='font-semibold text-white'>" . ($medali->emas ?? 0) . "</span></li>
                    <li>🥈 <span class='font-semibold text-white'>" . ($medali->perak ?? 0) . "</span></li>
                    <li>🥉 <span class='font-semibold text-white'>" . ($medali->perunggu ?? 0) . "</span></li>
                </ul>
            ")
        )
            ->description('Total medali yang diperoleh')
            ->descriptionIcon('heroicon-m-star')
            ->extraAttributes([
                'class' => '
                    bg-yellow-300
                    dark:bg-yellow-700
                    text-black
                    dark:text-white 
                    shadow-lg 
                    border-none 
                    rounded-xl 
                    transition 
                    duration-300 
                ',
            ]),
    ];
}

}