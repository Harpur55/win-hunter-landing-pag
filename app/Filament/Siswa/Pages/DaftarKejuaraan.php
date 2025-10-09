<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Kejuaraan; // pastikan model ini sudah ada
use Illuminate\Support\Facades\Auth;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Daftar Kejuaraan';

    protected static string $view = 'filament.siswa.pages.daftar-kejuaraan';

    public $kejuaraans = [];

    public function mount(): void
    {
        // Ambil semua event kejuaraan yang masih aktif / upcoming
        $this->kejuaraans = Kejuaraan::query()
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
    }
}
