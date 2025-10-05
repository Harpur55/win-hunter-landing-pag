<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventUjian;
use App\Models\User;
use App\Models\Siswa;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class UpdateBeltLevel extends Command
{
    protected $signature = 'belt:update {--instant}';
    protected $description = 'Update sabuk siswa otomatis setelah ujian (3 menit testing / H+1 production)';

    public function handle()
    {
        $updatedCount = 0;

        // 🔹 Pilih mode (instan / produksi)
        if ($this->option('instant')) {
            $this->info("⚡ Mode instan aktif: semua ujian akan diproses sekarang");
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

                // 🔹 Jika masih "on progres" / "on_proses" / "on-progress" → otomatis jadi "lulus"
                if (in_array($status, ['on_proses', 'on progres', 'on-progress'])) {
                    $event->siswa()->updateExistingPivot($siswa->id, [
                        'keterangan' => 'lulus',
                    ]);

                    $this->info("✅ {$siswa->nama_lengkap} status diubah ke LULUS.");
                    $pivot->keterangan = 'lulus'; // update in-memory
                    $status = 'lulus';
                }

                // 🔹 Jika sudah lulus → naikkan sabuk
                if ($status === 'lulus') {
                    if ($siswa->current_belt_level !== $pivot->next_belt_level) {
                        $siswa->update([
                            'current_belt_level' => $pivot->next_belt_level,
                            'status_lulus' => true, // Kolom opsional di tabel siswa
                        ]);

                        $updatedCount++;

                        // 🔔 Notifikasi untuk siswa
                        $user = User::find($siswa->user_id);
                        if ($user) {
                            Notification::make()
                                ->title('Selamat! 🎉')
                                ->body("Kamu telah **lulus ujian** dan sekarang bersabuk **{$pivot->next_belt_level}**.")
                                ->success()
                                ->sendToDatabase($user);
                        }

                        // 🔔 Notifikasi ke semua admin
                        $admins = User::where('role', 'admin')->get();
                        foreach ($admins as $admin) {
                            Notification::make()
                                ->title('Kenaikan Sabuk 🥋')
                                ->body("{$siswa->nama_lengkap} naik sabuk ke **{$pivot->next_belt_level}** setelah lulus ujian.")
                                ->success()
                                ->sendToDatabase($admin);
                        }

                        $this->info("🥋 {$siswa->nama_lengkap} berhasil naik ke {$pivot->next_belt_level}");
                    } else {
                        $this->warn("⏭ {$siswa->nama_lengkap} dilewati — sabuk sudah sesuai ({$pivot->next_belt_level}).");
                    }
                } elseif ($status !== 'lulus') {
                    $this->warn("⏭ {$siswa->nama_lengkap} dilewati karena status ujian = {$pivot->keterangan}");
                }
            }
        }

        $this->info("🎯 Total siswa naik sabuk: {$updatedCount}");
        return Command::SUCCESS;
    }
}
