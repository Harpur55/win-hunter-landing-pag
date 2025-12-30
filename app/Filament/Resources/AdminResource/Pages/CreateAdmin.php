<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected static ?string $title = 'Tambah Admin Baru';

    /**
     * Hook setelah data tersimpan — misalnya, kirim notifikasi.
     */
    protected function afterCreate(): void
    {
        // Contoh: kirim notifikasi atau log
        // Notification::make()
        //     ->title('Admin baru berhasil ditambahkan!')
        //     ->success()
        //     ->send();
    }
}
