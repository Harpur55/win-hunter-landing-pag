<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;

class HistoryUjian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
        protected static ?string $navigationGroup = 'UJIAN';

    protected static string $view = 'filament.siswa.pages.history-ujian';
}
