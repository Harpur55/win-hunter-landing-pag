<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\KejuaraanSiswa;
use Carbon\Carbon;

class HistoryKejuaraan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Riwayat Kejuaraan';

    protected static string $view = 'filament.siswa.pages.history-kejuaraan';

    public $riwayat = [];
    public $tahun = null;

    public function mount(): void
    {
        $siswa = Auth::user()->siswa;

         $this->tahun = request()->get('tahun');

        if ($siswa) {
            $this->riwayat = KejuaraanSiswa::with('kejuaraan')
                ->where('siswa_id', $siswa->id)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($item) {
                    return [
                        'nama_kejuaraan'       => $item->kejuaraan->nama_kejuaraan ?? '-',
                        'tanggal'              => $item->kejuaraan->tanggal_mulai
                            ? Carbon::parse($item->kejuaraan->tanggal_mulai)->translatedFormat('d F Y')
                            : '-',
                        'lokasi'               => $item->kejuaraan->lokasi ?? '-',
                        'medali'               => $item->medali ?? null,
                        'nama_peserta'         => $item->nama_lengkap ?? '-', // dari kejuaraan_siswa
                        'kategori_pertandingan'=> $item->kategori_pertandingan ?? '-',
                    ];
                })
                ->toArray();
        }
    }
}
