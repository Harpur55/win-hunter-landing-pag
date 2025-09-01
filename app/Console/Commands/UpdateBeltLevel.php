<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventUjian;
use App\Models\User;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class UpdateBeltLevel extends Command
{
    protected $signature = 'belt:update {--instant}';
    protected $description = 'Update sabuk siswa otomatis setelah ujian (3 menit testing / H+1 production)';

    public function handle()
    {
        $updatedCount = 0;

        if ($this->option('instant')) {
            $this->info("⚡ Mode instan aktif: semua ujian akan diproses sekarang");
            $events = EventUjian::with('siswa')->get();
        } else {
            // Testing: update setelah 3 menit pendaftaran
            $events = EventUjian::where('created_at', '<=', Carbon::now()->subMinutes(3))
                ->with('siswa')
                ->get();

            // Production (H+1)
            // $events = EventUjian::whereDate('tanggal_ujian', '<=', Carbon::yesterday())
            //     ->with('siswa')
            //     ->get();
        }

        foreach ($events as $event) {
            foreach ($event->siswa as $siswa) {
                $pivot = $siswa->pivot;

                // 🔹 Jika masih "on_proses" → otomatis jadi "lulus" setelah waktu tunggu
                if ($pivot->keterangan === 'on_proses') {
                    $event->siswa()->updateExistingPivot($siswa->id, [
                        'keterangan' => 'lulus',
                    ]);
                    $this->info("⏳ {$siswa->nama_lengkap} otomatis diubah ke status LULUS (auto setelah waktu tunggu).");
                    $pivot->keterangan = 'lulus'; // update in-memory supaya langsung bisa diproses
                }

                // 🔹 Hanya proses siswa yang lulus
                if ($pivot->keterangan !== 'lulus') {
                    $this->warn("⏭ {$siswa->nama_lengkap} dilewati karena status ujian = {$pivot->keterangan}");
                    continue;
                }

                // 🔹 Jika sabuk master sudah sama → skip
                if ($siswa->current_belt_level === $pivot->next_belt_level) {
                    $this->warn("⏭ {$siswa->nama_lengkap} dilewati karena sabuk master sudah = {$pivot->next_belt_level}");
                    continue;
                }

                // ✅ Update sabuk master → naik
                $siswa->update([
                    'current_belt_level' => $pivot->next_belt_level,
                ]);

                // ❌ Pivot tidak diubah → biarkan menyimpan sabuk lama (riwayat)

                $updatedCount++;

                // ✅ Notifikasi ke admin
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::make()
                        ->title('Kenaikan Sabuk')
                        ->body("{$siswa->nama_lengkap} naik sabuk ke **{$pivot->next_belt_level}** setelah lulus UKT.")
                        ->success()
                        ->sendToDatabase($admin);
                }

                $this->info("✅ {$siswa->nama_lengkap} berhasil naik ke {$pivot->next_belt_level}");
            }
        }

        $this->info("🎯 Total siswa naik sabuk: {$updatedCount}");
        return Command::SUCCESS;
    }
}
