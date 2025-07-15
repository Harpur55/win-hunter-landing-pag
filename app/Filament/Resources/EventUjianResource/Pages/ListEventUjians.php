<?php

namespace App\Filament\Resources\EventUjianResource\Pages;

use App\Filament\Resources\EventUjianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventUjians extends ListRecords
{
    protected static string $resource = EventUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
