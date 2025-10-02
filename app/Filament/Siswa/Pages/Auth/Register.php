<?php

namespace App\Filament\Siswa\Pages\Auth;

use App\Models\User;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    protected static ?string $title = 'Register';
    protected ?string $heading = 'Sacti Win Hunter Register';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
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

        DB::transaction(function () use ($data, &$user) {
            // 1. Buat user baru
            $user = User::create([
                'name'     => $data['nama_lengkap'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // 2. Assign role siswa
            $user->assignRole('siswa');

            // 3. Cek apakah nama sudah ada di tabel siswas
            $siswa = Siswa::where('nama_lengkap', 'like', "%{$data['nama_lengkap']}%")->first();

            if ($siswa) {
                // Jika ada, update untuk hubungkan dengan user baru
                $siswa->update([
                    'user_id' => $user->id,
                ]);
            } else {
                // Jika belum ada, buat siswa baru
                $user->siswa()->create([
                    'nama_lengkap' => $data['nama_lengkap'],
                ]);
            }
        });

        // Notifikasi sukses
        Notification::make()
            ->title('Registrasi Berhasil')
            ->success()
            ->body('Akun berhasil dibuat. Silakan login.')
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
