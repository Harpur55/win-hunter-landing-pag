<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventUjian;
use App\Models\User;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class UpdateBeltLevel extends Command
{
    protected $signature = 'belt:update {--instant}';
    protected $description = 'Update sabuk siswa otomatis setelah ujian';

    public function handle()
    {
        $updatedCount = 0;

        // Mode Instan atau Production
        if ($this->option('instant')) {
            $this->info("⚡ Mode instan aktif: memproses semua event ujian");
            $events = EventUjian::with('siswa')->get();
        } else {
            $events = EventUjian::where('created_at', '<=', Carbon::now()->subMinutes(3))
                ->with('siswa')
                ->get();
        }

        foreach ($events as $event) {

            foreach ($event->siswa as $siswa) {
                $pivot = $siswa->pivot;
                $status = strtolower(trim($pivot->keterangan));

                // ============================
                // 1️⃣ AUTOFIX STATUS — ON PROGRES → LULUS
                // ============================
                if (in_array($status, ['on_proses', 'on progres', 'on-progress'])) {

                    $event->siswa()->updateExistingPivot($siswa->id, [
                        'keterangan' => 'lulus'
                    ]);

                    $this->info("🔄 Status {$siswa->nama_lengkap} diperbaiki ke 'lulus'.");
                    $status = 'lulus';
                }

                // ============================
                // 2️⃣ JIKA LULUS → NAIKKAN SABUK
                // ============================
                if ($status === 'lulus') {

                    // Hanya update jika different
                    if ($siswa->current_belt_level !== $pivot->next_belt_level) {

                        $siswa->update([
                            'current_belt_level' => $pivot->next_belt_level,
                            'status_lulus'       => true,
                        ]);

                        $updatedCount++;

                        // Notifikasi ke user
                        if ($user = User::find($siswa->user_id)) {
                            Notification::make()
                                ->title('Selamat! 🎉')
                                ->body("Kamu sekarang naik sabuk menjadi **{$pivot->next_belt_level}**.")
                                ->success()
                                ->sendToDatabase($user);
                        }

                        // Notifikasi admin
                        User::where('role', 'admin')->get()
                            ->each(function ($admin) use ($siswa, $pivot) {
                                Notification::make()
                                    ->title('Kenaikan Sabuk 🥋')
                                    ->body("{$siswa->nama_lengkap} naik sabuk ke **{$pivot->next_belt_level}**.")
                                    ->success()
                                    ->sendToDatabase($admin);
                            });

                        $this->info("🥋 {$siswa->nama_lengkap} naik sabuk ke {$pivot->next_belt_level}");

                    } else {
                        $this->warn("⏭ {$siswa->nama_lengkap} dilewati — sabuknya sudah benar.");
                    }

                    continue; // Selesai, lanjut siswa berikutnya
                }

                // ============================
                // 3️⃣ JIKA TIDAK LULUS → ABAIKAN
                // ============================
                $this->warn("⏭ {$siswa->nama_lengkap} tidak lulus — sabuk **TIDAK diubah**");
            }
        }

        $this->info("🎯 Total siswa naik sabuk: {$updatedCount}");
        return Command::SUCCESS;
    }
}
