<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;

class DaftarUjian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
        protected static ?string $navigationGroup = 'UJIAN';

    protected static string $view = 'filament.siswa.pages.daftar-ujian';
}
