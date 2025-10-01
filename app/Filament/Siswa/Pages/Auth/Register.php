<?php

namespace App\Filament\Siswa\Pages\Auth;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    protected static ?string $title = 'Register';
    protected ?string $heading = 'Sacti Win Hunter Register';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(User::class, 'email')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->confirmed()
                    ->minLength(6),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        // Simpan user baru ke tabel users
        $user = User::create([
            'name'     => $data['name'],  // simpan nama lengkap sesuai input
            'email'    => $data['email'],
            'password' => Hash::make($data['password']), // cast otomatis hash
        ]);

        // Assign role 'siswa'
        $user->assignRole('siswa');

        // Notifikasi sukses
        Notification::make()
            ->title('Registrasi Berhasil')
            ->success()
            ->body('Akun berhasil dibuat sebagai siswa. Silakan login.')
            ->send();

        // Redirect ke login siswa
        return new class implements RegistrationResponse {
            public function toResponse($request)
            {
                return redirect()->route('filament.siswa.auth.login')
                    ->with('success', 'Registrasi berhasil. Silakan login.');
            }
        };
    }
}
