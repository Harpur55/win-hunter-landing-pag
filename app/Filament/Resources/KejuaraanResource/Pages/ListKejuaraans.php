<?php

namespace App\Filament\Resources\KejuaraanResource\Pages;

use App\Filament\Resources\KejuaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKejuaraans extends ListRecords
{
    protected static string $resource = KejuaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
