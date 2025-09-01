<?php

namespace App\Filament\Resources\KejuaraanResource\Pages;

use App\Filament\Resources\KejuaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKejuaraan extends EditRecord
{
    protected static string $resource = KejuaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
