<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
     protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'pages.dashboard';

    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard admin win Hunter';
    protected static ?string $slug = 'dashboard';
    protected static ?int $navigationSort = -1;
}
