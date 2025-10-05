<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\EventUjian;

class HistoryUjian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'UJIAN';
    protected static ?string $navigationLabel = 'Riwayat Ujian';
    protected static ?string $title = 'Riwayat Ujian yang Telah Diikuti';

    protected static string $view = 'filament.siswa.pages.history-ujian';

    public $riwayat = [];

    public function mount()
    {
        $siswa = Auth::user()->siswa;

        if ($siswa) {
            $this->riwayat = EventUjian::whereHas('siswa', function ($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })
                ->with(['siswa' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                }])
                ->orderByDesc('tanggal_ujian')
                ->get();
        }
    }
}
