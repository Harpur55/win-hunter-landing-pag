<?php

namespace App\Filament\Resources\KejuaraanResource\Pages;

use App\Filament\Resources\KejuaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateKejuaraan extends CreateRecord
{
    protected static string $resource = KejuaraanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // generate slug unik
        $slug = Str::slug($data['nama_kejuaraan']);

        $count = \App\Models\Kejuaraan::where('slug', 'like', "{$slug}%")->count();
        if ($count) {
            $slug .= '-' . ($count + 1);
        }

        $data['slug'] = $slug;

        return $data;
    }
}
