<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->hasRole(['admin', 'super-admin']);
    }

    if ($panel->getId() === 'siswa') {
        return $this->hasRole('siswa');
    }

    return false;
}

   public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

}
