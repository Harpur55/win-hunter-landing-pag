<?php

namespace App\Filament\Resources\EventUjianResource\Pages;

use App\Filament\Resources\EventUjianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventUjian extends EditRecord
{
    protected static string $resource = EventUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
