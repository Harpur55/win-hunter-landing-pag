<?php

namespace App\Filament\Siswa\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;

class WelcomeWidgetSiswa extends Widget
{
    protected static string $view = 'filament.siswa.widgets.welcome-widget-siswa';
    protected int|string|array $columnSpan = 'full';
    protected static bool $isLazy = false;

    public function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        $nama  = $siswa->nama_lengkap ?? $user->name ?? 'User';
        $kelas = $siswa?->kelas?->name ?? '-';
        $kuota = [];

        if ($siswa && $siswa->kelas) {
            $kelasModel = $siswa->kelas;

            // Jika siswa belum punya sisa_kuota, set otomatis dari kuota_awal
            if (is_null($siswa->sisa_kuota)) {
                $siswa->update([
                    'sisa_kuota' => $kelasModel->kuota_awal ?? 0,
                ]);
            }

            // Ambil langsung dari tabel siswa (real-time)
            $sisaKuota = $siswa->sisa_kuota ?? 0;

            $kuota = [
                strtolower($kelasModel->name) => $sisaKuota,
            ];
        }

        return compact('nama', 'kelas', 'kuota');
    }
}
