<?php

namespace App\Filament\Siswa\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KejuaraanSiswa;

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
        $current_belt_level = $siswa?->current_belt_level ?? '-';
        $kuota = [];

        if ($siswa && $siswa->kelas) {
            $kelasModel = $siswa->kelas;

            // Jika siswa belum punya sisa_kuota, set otomatis dari kuota_awal
            if (is_null($siswa->sisa_kuota)) {
                $siswa->update([
                    'sisa_kuota' => $kelasModel->kuota_awal ?? 0,
                ]);
            }

            // ✅ Hitung KUOTA TERPAKAI yang BENAR-BENAR MENGGUNAKAN KUOTA
            $totalPendaftaranPakaiKuota = KejuaraanSiswa::where('siswa_id', $siswa->id)
                ->where('use_kuota', true)
                ->count();

            $kuotaAwal = $kelasModel->kuota_awal ?? 0;
            $sisaKuotaReal = max(0, $kuotaAwal - $totalPendaftaranPakaiKuota);

            // Sinkronkan sisa_kuota di database dengan perhitungan real
            if ($siswa->sisa_kuota != $sisaKuotaReal) {
                $siswa->update(['sisa_kuota' => $sisaKuotaReal]);
            }

            $kuota = [
                strtolower($kelasModel->name) => $sisaKuotaReal,
            ];
        }

        return compact('nama', 'kelas', 'kuota', 'current_belt_level');
    }
}
