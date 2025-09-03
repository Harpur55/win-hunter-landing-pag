<?php

namespace App\Livewire\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'livewire.auth.custom-login';
}
