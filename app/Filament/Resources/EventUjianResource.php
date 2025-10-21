<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventUjianResource\Pages;
use App\Filament\Resources\EventUjianResource\RelationManagers;
use App\Models\EventUjian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventUjianExport;

class EventUjianResource extends Resource
{
    protected static ?string $model = EventUjian::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Ujian Kenaikan Tingkat';
    protected static ?string $navigationLabel = 'Event Ujian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_ujian')
                    ->label('Nama Event Ujian')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('tanggal_ujian')
                    ->label('Tanggal Ujian')
                    ->required(),

                TextInput::make('lokasi_ujian')
                    ->label('Lokasi Ujian')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_ujian')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_ujian')
                    ->label('Tanggal Ujian')
                    ->date()
                    ->sortable(),


                TextColumn::make('lokasi_ujian')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('is_registration_closed')
                    ->label('Status Pendaftaran')
                    ->formatStateUsing(function (bool $state) {
                        return $state
                            ? '🔒 Tertutup'
                            : '🔓 Terbuka';
                    })
                    ->badge()
                    ->color(fn(bool $state) => $state ? 'danger' : 'success'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // ✅ Tombol Toggle Pendaftaran di setiap baris
                // Action::make('toggle_registration')
                //     ->label(fn(EventUjian $record) => $record->is_registration_closed
                //         ? 'Buka Pendaftaran'
                //         : 'Tutup Pendaftaran')
                //     ->icon(fn(EventUjian $record) => $record->is_registration_closed
                //         ? 'heroicon-o-check-circle'
                //         : 'heroicon-o-x-circle')
                //     ->color(fn(EventUjian $record) => $record->is_registration_closed
                //         ? 'success'
                //         : 'danger')
                //     ->requiresConfirmation()
                //     ->action(function (EventUjian $record) {
                //         $newStatus = !$record->is_registration_closed;

                //         $record->update([
                //             'is_registration_closed' => $newStatus,
                //         ]);

                //         Notification::make()
                //             ->title($newStatus ? 'Pendaftaran Ditutup' : 'Pendaftaran Dibuka')
                //             ->body('Pendaftaran untuk ' . $record->nama_ujian . ' telah ' . ($newStatus ? 'ditutup.' : 'dibuka kembali.'))
                //             ->success()
                //             ->send();
                //     }),
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
            RelationManagers\SiswaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventUjians::route('/'),
            'create' => Pages\CreateEventUjian::route('/create'),
            'view' => Pages\ViewEventUjian::route('/{record}'),
            'edit' => Pages\EditEventUjian::route('/{record}/edit'),
        ];
    }
}
