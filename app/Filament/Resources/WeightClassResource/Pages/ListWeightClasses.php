<?php

namespace App\Filament\Resources\WeightClassResource\Pages;

use App\Filament\Resources\WeightClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWeightClasses extends ListRecords
{
    protected static string $resource = WeightClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
