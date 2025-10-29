<?php

namespace App\Filament\Siswa\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\KejuaraanSiswa;

class WelcomeWidgetSiswa extends Widget
{
    protected static string $view = 'filament.siswa.widgets.welcome-widget-siswa';
    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        $nama  = $siswa->nama_lengkap ?? $user->name ?? 'User';
        $kelas = strtolower($siswa->kelas->name ?? '-');

        /**
         * 🎯 Kuota maksimal berdasarkan kelas siswa
         */
        $kuotaMaks = [
            'prestasi' => 4,
            'khusus'   => 3,
            'reguler'  => 2,
        ];

        $kuota = [];

        if (isset($kuotaMaks[$kelas])) {
            $maks = $kuotaMaks[$kelas];

            // Hitung jumlah kejuaraan yang sudah diikuti
            $jumlahTerdaftar = KejuaraanSiswa::where('siswa_id', $siswa->id)->count();

            // Hitung kuota tersisa
            $tersisa = max($maks - $jumlahTerdaftar, 0);

            $kuota[$kelas] = $tersisa;
        }

        return compact('nama', 'kelas', 'kuota');
    }
}
