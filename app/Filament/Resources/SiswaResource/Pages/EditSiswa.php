<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use App\Models\SiswaCuti;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

      protected function afterSave(): void
    {
        $data  = $this->data;
        $siswa = $this->record;

        /* =========================
         | JIKA STATUS = CUTI
         ========================= */
        if (
            ($data['status'] ?? null) === 'Cuti'
            && isset($data['cuti']['tanggal_mulai'])
        ) {
            // Tutup cuti aktif lama (jika ada)
            SiswaCuti::where('siswa_id', $siswa->id)
                ->where('status', 'aktif')
                ->update([
                    'status'           => 'selesai',
                    'tanggal_selesai'  => now()->toDateString(),
                ]);

            // Buat cuti baru
            SiswaCuti::create([
                'siswa_id'        => $siswa->id,
                'tanggal_mulai'   => $data['cuti']['tanggal_mulai'],
                'tanggal_selesai' => $data['cuti']['tanggal_selesai'] ?? null,
                'alasan'          => $data['cuti']['alasan'] ?? null,
                'status'          => 'aktif',
            ]);

            return;
        }

        /* =========================
         | JIKA STATUS ≠ CUTI
         ========================= */
        if (in_array($data['status'], ['Aktif', 'Tidak Aktif'])) {
            SiswaCuti::where('siswa_id', $siswa->id)
                ->where('status', 'aktif')
                ->update([
                    'status'          => 'selesai',
                    'tanggal_selesai' => now()->toDateString(),
                ]);
        }
    }
}
