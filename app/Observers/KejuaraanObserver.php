<?php

namespace App\Observers;

use App\Models\Kejuaraan;
use App\Models\HistoryKejuaraan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KejuaraanObserver
{
    /**
     * Jalankan setelah kejuaraan dibuat atau diperbarui.
     */
    public function saved(Kejuaraan $kejuaraan): void
    {
        // Jalankan hanya jika tanggal_mulai valid dan sudah lewat
        if (blank($kejuaraan->tanggal_mulai) || now()->lte($kejuaraan->tanggal_mulai)) {
            return;
        }

        try {
            DB::transaction(function () use ($kejuaraan) {
                // Ambil peserta yang punya medali
                $peserta = $kejuaraan->siswa()
                    ->whereNotNull('kejuaraan_siswa.medali')
                    ->where('kejuaraan_siswa.medali', '!=', 'tidak_ada')
                    ->get();

                foreach ($peserta as $siswa) {
                    // Cek apakah sudah tercatat di history
                    $sudahAda = HistoryKejuaraan::where([
                        ['kejuaraan_id', '=', $kejuaraan->id],
                        ['siswa_id', '=', $siswa->id],
                    ])->exists();

                    if ($sudahAda) {
                        continue;
                    }

                    // Simpan riwayat
                    HistoryKejuaraan::create([
                        'kejuaraan_id'           => $kejuaraan->id,
                        'siswa_id'               => $siswa->id,
                        'nama_kejuaraan'         => $kejuaraan->nama_kejuaraan,
                        'tanggal'                => $kejuaraan->tanggal_mulai,
                        'lokasi'                 => $kejuaraan->lokasi ?? '-',
                        'nama_peserta'           => $siswa->pivot->nama_lengkap ?? $siswa->nama_lengkap ?? '-',
                        'kategori_pertandingan'  => $siswa->pivot->kategori_pertandingan ?? '-',
                        'kategori_atlit'         => $siswa->pivot->kategori_atlit ?? '-',
                        'medali'                 => $siswa->pivot->medali ?? 'tidak_ada',
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[Observer:Kejuaraan] Gagal simpan history: ' . $e->getMessage(), [
                'kejuaraan_id' => $kejuaraan->id,
            ]);
        }
    }
}
