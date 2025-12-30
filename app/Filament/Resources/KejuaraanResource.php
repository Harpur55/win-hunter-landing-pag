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
                    ->label('Nama Kejuaraan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('grades')
                    ->label('Grades')
                    ->options([
                        'nasional_A'          => 'Nasional A',
                        'nasional_B'          => 'Nasional B',
                        'daerah_A'            => 'Daerah A',
                        'daerah_B'            => 'Daerah B',
                        'tryout_antar_club'   => 'Tryout Antar Club',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),

                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

             ->recordUrl(fn ($record) =>
            KejuaraanResource::getUrl('view', ['record' => $record])
        )
            ->columns([
                Tables\Columns\TextColumn::make('nama_kejuaraan')
                    ->label('Nama Kejuaraan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grades')
                    ->label('Grades')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'nasional_A'        => 'Nasional A',
                        'nasional_B'        => 'Nasional B',
                        'daerah_A'          => 'Daerah A',
                        'daerah_B'          => 'Daerah B',
                        'tryout_antar_club' => 'Tryout Antar Club',
                        default             => $state,
                    }),

                Tables\Columns\IconColumn::make('is_registration_closed')
                    ->label('Status Pendaftaran')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('slug')
                //     ->label('Link Pendaftaran')
                //     ->state(fn($record) => route('kejuaraan.daftar', $record->slug))
                //     ->url(fn($state) => $state)
                //     ->openUrlInNewTab()
                //     ->copyable()
                //     ->icon('heroicon-o-link')
                //     ->limit(40),
            ])



            ->filters([
                Tables\Filters\SelectFilter::make('tahun')
                    ->label('Tahun Kejuaraan')
                    ->options(function () {
                        // Ambil daftar tahun unik dari kolom tanggal_mulai
                        return \App\Models\Kejuaraan::selectRaw('YEAR(tanggal_mulai) as tahun')
                            ->distinct()
                            ->orderByDesc('tahun')
                            ->pluck('tahun', 'tahun')
                            ->toArray();
                    })
                    ->query(function ($query, $data) {
                        // Terapkan filter berdasarkan tahun yang dipilih
                        if (!empty($data['value'])) {
                            $query->whereYear('tanggal_mulai', $data['value']);
                        }
                    }),
            ])


            ->actions([

                 Tables\Actions\EditAction::make()
        ->label('Edit')
        ->icon('heroicon-o-pencil-square'),
                // ✅ Tombol Tutup/Buka Pendaftaran
                Action::make('toggle_registration')
                    ->label(fn($record) => $record->is_registration_closed ? 'Buka Pendaftaran' : 'Tutup Pendaftaran')
                    ->color(fn($record) => $record->is_registration_closed ? 'success' : 'danger')
                    ->icon(fn($record) => $record->is_registration_closed ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
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

                // ✅ Tombol Ubah ke Selesai
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
            'index' => Pages\ListKejuaraans::route('/'),
            'create' => Pages\CreateKejuaraan::route('/create'),
            'view' => Pages\ViewKejuaraan::route('/{record}'),
            'edit' => Pages\EditKejuaraan::route('/{record}/edit'),
        ];
    }
}
