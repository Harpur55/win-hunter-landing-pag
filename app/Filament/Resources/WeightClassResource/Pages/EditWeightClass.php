<?php

namespace App\Filament\Resources\WeightClassResource\Pages;

use App\Filament\Resources\WeightClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeightClass extends EditRecord
{
    protected static string $resource = WeightClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
