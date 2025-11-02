<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KejuaraanResource\Pages;
use App\Filament\Resources\KejuaraanResource\RelationManagers;
use App\Models\Kejuaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KejuaraanResource extends Resource
{
    protected static ?string $model = Kejuaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Data Kejuaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kejuaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->required(),
                Forms\Components\TextInput::make('lokasi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kejuaraan')->label('Nama Kejuaraan')->searchable(),
            Tables\Columns\IconColumn::make('is_registration_closed')
                ->label('Status Pendaftaran')
                ->boolean()
                ->trueIcon('heroicon-o-lock-closed')
                ->falseIcon('heroicon-o-lock-open')
                ->trueColor('danger')
                ->falseColor('success'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                  Action::make('toggle_registration')
                ->label(fn ($record) => $record->is_registration_closed ? 'Buka Pendaftaran' : 'Tutup Pendaftaran')
                ->color(fn ($record) => $record->is_registration_closed ? 'success' : 'danger')
                ->icon(fn ($record) => $record->is_registration_closed ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->is_registration_closed = ! $record->is_registration_closed;
                    $record->save();

                    Notification::make()
                        ->title($record->is_registration_closed 
                            ? '⛔ Pendaftaran telah ditutup.' 
                            : '✅ Pendaftaran telah dibuka kembali.')
                        ->success()
                        ->send();
                }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\SiswaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKejuaraans::route('/'),
            'create' => Pages\CreateKejuaraan::route('/create'),
            'edit' => Pages\EditKejuaraan::route('/{record}/edit'),
        ];
    }
}
