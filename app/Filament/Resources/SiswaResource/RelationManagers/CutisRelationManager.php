<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class CutisRelationManager extends RelationManager
{
    protected static string $relationship = 'cutis';

    protected static ?string $title = 'Riwayat Cuti';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai Cuti')
                ->required()
                ->native(false),

            DatePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->nullable()
                ->native(false)
                ->helperText('Boleh dikosongkan jika cuti belum selesai'),

            Select::make('status')
                ->options([
                    'aktif' => 'Aktif',
                    'selesai' => 'Selesai',
                ])
                ->default('aktif')
                ->required(),

            Textarea::make('alasan')
                ->label('Alasan Cuti')
                ->rows(2)
                ->nullable(),

            Textarea::make('keterangan')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_mulai')->date(),
                TextColumn::make('tanggal_selesai')
                    ->date()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'aktif' ? 'warning' : 'success'),

                TextColumn::make('alasan')->limit(30),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
