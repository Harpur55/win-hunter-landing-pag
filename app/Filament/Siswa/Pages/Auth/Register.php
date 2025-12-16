<?php

namespace App\Filament\Siswa\Pages\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;

class Register extends BaseRegister
{
    protected static ?string $title = 'Register';
    protected ?string $heading = 'Sacti Win Hunter Register';
    protected static string $view = 'filament.siswa.auth.register';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(255)
                ->extraAttributes(['autocomplete' => 'off']),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(table: User::class, column: 'email', ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->revealable()
                ->confirmed()
                ->minLength(6),

            Forms\Components\TextInput::make('password_confirmation')
                ->label('Konfirmasi Password')
                ->password()
                ->required()
                ->revealable(),
        ])
        ->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        /** 📌 Rate Limit: 5 kali percobaan / menit */
        $ip = request()->ip();
        $key = 'register:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            Notification::make()
                ->title('Terlalu Banyak Percobaan')
                ->danger()
                ->body("Silakan coba lagi dalam $seconds detik.")
                ->send();

            return null;
        }

        RateLimiter::hit($key, 60);

        /** 🛡 Buat Akun Baru */
        DB::transaction(function () use ($data, &$user) {

            $user = User::create([
                'name'  => e($data['nama_lengkap']),
                'email' => strtolower($data['email']),
                'password' => Hash::make($data['password']),
                'is_profile_completed' => false, // dipakai agar diarahkan ke verifikasi profile
            ]);

            // Role siswa
            $user->assignRole('siswa');
        });

        /** 🎉 Notifikasi Berhasil */
        Notification::make()
            ->title('Registrasi Berhasil')
            ->success()
            ->body('Akun berhasil dibuat. Silakan login.')
            ->send();

        /** 🔀 Redirect ke login */
        return new class implements RegistrationResponse {
            public function toResponse($request)
            {
                return redirect()->route('filament.siswa.auth.login')
                    ->with('success', 'Registrasi berhasil. Silakan login.');
            }
        };
    }
}
