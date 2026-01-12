<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use App\Models\SiswaCuti;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

     protected function afterCreate(): void
    {
        $data = $this->data;

        // Jika status CUTI → langsung simpan ke siswa_cutis
        if (
            ($data['status'] ?? null) === 'Cuti'
            && isset($data['cuti']['tanggal_mulai'])
        ) {
            SiswaCuti::create([
                'siswa_id'        => $this->record->id,
                'tanggal_mulai'   => $data['cuti']['tanggal_mulai'],
                'tanggal_selesai' => $data['cuti']['tanggal_selesai'] ?? null,
                'alasan'          => $data['cuti']['alasan'] ?? null,
                'status'          => 'aktif', // ✅ langsung cuti aktif
            ]);
        }
    }
}

