<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Setting';
    protected static ?string $navigationLabel = 'Ubah Password';
    protected static ?string $title = 'Ubah Password';
    protected static string $view = 'filament.siswa.pages.setting';

    public $old_password;
    public $new_password;
    public $confirm_password;

    public function changePassword()
    {
        $this->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'confirm_password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::guard('siswa')->user();

        if (! Hash::check($this->old_password, $user->password)) {
            Notification::make()
                ->title('Password lama salah!')
                ->danger()
                ->send();

            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['old_password', 'new_password', 'confirm_password']);

        Notification::make()
            ->title('Password berhasil diubah!')
            ->success()
            ->send();
    }
}
