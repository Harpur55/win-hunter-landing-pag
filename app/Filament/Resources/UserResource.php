<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Sistem';
    protected static ?string $navigationLabel = 'User Management';

    /**
     * Hanya Super Admin yang bisa buka menu ini
     */
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole('super-admin');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(fn ($record) => $record === null) // wajib saat create
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state)),

            // Role pakai relasi Spatie
            Forms\Components\Select::make('roles')
                ->label('Role')
                ->relationship('roles', 'name') // relasi dari HasRoles
                ->searchable()
                ->preload()
                ->multiple(false) // 1 role per user, kalau mau multi-role ganti true
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                // Ambil role dari Spatie
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'super-admin' => 'danger',
                        'admin'       => 'success',
                        'siswa'       => 'primary',
                        default       => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                // Filter berdasarkan role
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit'   => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
