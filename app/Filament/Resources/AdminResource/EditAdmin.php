<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected static ?string $title = 'Edit Data Admin';

    /**
     * Hook setelah data berhasil diupdate.
     */
    protected function afterSave(): void
    {
        // Contoh: kirim notifikasi atau log aktivitas
        // Notification::make()
        //     ->title('Data admin berhasil diperbarui!')
        //     ->success()
        //     ->send();
    }

    /**
     * Kustomisasi tombol di header (opsional).
     */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
