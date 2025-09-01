<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command Artisan custom.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\UpdateBeltLevel::class, // <- Command custom yang kita buat
    ];

    /**
     * Jadwal command yang akan dijalankan.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan update sabuk otomatis setiap hari jam 00:00
        // $schedule->command('belt:update')->dailyAt('00:00');


        // Contoh lain kalau mau testing tiap menit
        $schedule->command('belt:update')->everyMinute();
    }

    /**
     * Daftar file artisan command.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
