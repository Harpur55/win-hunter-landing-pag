<?php

namespace App\Filament\Resources\EventUjianResource\Pages;

use App\Filament\Resources\EventUjianResource;
use Filament\Actions; // Jika Anda perlu menambahkan action di header halaman view
use Filament\Resources\Pages\ViewRecord;

class ViewEventUjian extends ViewRecord
{
    // Properti statis ini adalah yang terpenting.
    // Ini memberi tahu Filament, Resource mana yang bertanggung jawab untuk
    // mendefinisikan form yang akan ditampilkan dalam mode view (read-only).
    protected static string $resource = EventUjianResource::class;

    // Anda bisa menambahkan actions ke header halaman view di sini jika diperlukan.
    // Misalnya, tombol "Edit" atau tombol kustom lainnya.
    protected function getHeaderActions(): array
    {
        return [
            // Contoh: Tambahkan tombol edit di header halaman view
            Actions\EditAction::make(),
          
        ];
    }
}