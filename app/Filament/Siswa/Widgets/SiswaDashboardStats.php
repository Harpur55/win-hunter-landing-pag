<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SiswaDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return [
                Stat::make('Data Tidak Ditemukan', 'Silakan lengkapi profil dulu')
                    ->description('Profil siswa belum ada')
                    ->color('danger'),
            ];
        }

        // 🔹 Event ujian yang diikuti siswa (hanya satu)
        $eventUjian = $siswa->ujian()
            ->orderBy('tanggal_ujian', 'asc')
            ->first();

        // 🔹 Kejuaraan
        $kejuaraan = $siswa->kejuaraan()
            ->whereDate('tanggal_mulai', '>=', Carbon::today())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // 🔹 Hitung medali
        $medali = DB::table('kejuaraan_siswa')
            ->where('siswa_id', $siswa->id)
            ->selectRaw("
                SUM(CASE WHEN medali = 'emas' THEN 1 ELSE 0 END) as emas,
                SUM(CASE WHEN medali = 'perak' THEN 1 ELSE 0 END) as perak,
                SUM(CASE WHEN medali = 'perunggu' THEN 1 ELSE 0 END) as perunggu
            ")
            ->first();

        return [
            // ✅ Event Ujian
            Stat::make(
                'UJIAN yang diikuti',
                $eventUjian?->nama_ujian ?? 'Kamu belum daftar ujian sekarang'
            )
                ->description(
                    $eventUjian
                        ? "📅 " . Carbon::parse($eventUjian->tanggal_ujian)->format('d-m-Y') .
                          " | 📍 " . $eventUjian->lokasi_ujian
                        : 'Belum ada jadwal ujian'
                )
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($eventUjian ? 'primary' : 'gray'),

            // ✅ Kejuaraan
            Stat::make(
                'Kejuaraan yang diikuti',
                $kejuaraan?->nama_kejuaraan ?? 'Kamu belum daftar kejuaraan sekarang'
            )
                ->description(
                    $kejuaraan
                        ? "📅 " . Carbon::parse($kejuaraan->tanggal_mulai)->format('d-m-Y') .
                          " s/d " . Carbon::parse($kejuaraan->tanggal_selesai)->format('d-m-Y')
                        : 'Belum ada jadwal kejuaraan'
                )
                ->descriptionIcon('heroicon-m-trophy')
                ->color($kejuaraan ? 'success' : 'gray'),

            // ✅ Medali
            Stat::make(
                'Jumlah Medali',
                new HtmlString("🥇 {$medali->emas} | 🥈 {$medali->perak} | 🥉 {$medali->perunggu}")
            )
                ->description('Total medali yang diperoleh')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
