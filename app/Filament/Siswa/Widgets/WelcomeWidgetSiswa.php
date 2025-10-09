<?php

namespace App\Filament\Siswa\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class WelcomeWidgetSiswa extends Widget
{
    protected static string $view = 'filament.siswa.widgets.welcome-widget-siswa';
    protected int|string|array $columnSpan = 'full'; // full width

    public function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        // Default jika data kosong
        $nama = $siswa->nama_lengkap ?? $user->name ?? 'User';
        $kelas = $siswa->kelas->name ?? '-';

        // Semua jenis kuota
        $semuaKuota = [
            'prestasi' => 4,
            'khusus'   => 2,
            'reguler'  => 1,
            'poomsae'  => 3,
        ];

        // 🔹 Hanya tampilkan kuota sesuai kelas siswa
        $kuota = match (strtolower($kelas)) {
            'prestasi' => ['prestasi' => $semuaKuota['prestasi']],
            'khusus'   => ['khusus'   => $semuaKuota['khusus']],
            'reguler'  => ['reguler'  => $semuaKuota['reguler']],
            'poomsae'  => ['poomsae'  => $semuaKuota['poomsae']],
            default    => [],
        };

        return compact('nama', 'kelas', 'kuota');
    }
}
