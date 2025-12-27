<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\EventUjian;
use Illuminate\Support\Facades\Storage;

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
            // ✅ LOAD UJIAN SISWA + SERTIFIKAT
            ->with(['ujianSiswa' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id)
                  ->with('sertifikat'); // Load sertifikat
            }])
            ->orderByDesc('tanggal_ujian')
            ->get();
        }
    }

    // ✅ HELPER: Cek sertifikat ada & aktif
    public function hasSertifikat($event): bool
    {
        return $event->ujianSiswa->first()?->sertifikat?->is_active ?? false;
    }

    // ✅ HELPER: URL download sertifikat
    public function getSertifikatUrl($event): ?string
    {
        $sertifikat = $event->ujianSiswa->first()?->sertifikat;
        return $sertifikat?->file_pdf ? Storage::url($sertifikat->file_pdf) : null;
    }

    // ✅ HELPER: No sertifikat
    public function getNoSertifikat($event): ?string
    {
        return $event->ujianSiswa->first()?->sertifikat?->no_sertifikat ?? null;
    }
}
