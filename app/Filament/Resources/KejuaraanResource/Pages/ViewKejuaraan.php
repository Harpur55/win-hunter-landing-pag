<?php

namespace App\Filament\Resources\KejuaraanResource\Pages;

use App\Filament\Resources\KejuaraanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class ViewKejuaraan extends ViewRecord
{
    protected static string $resource = KejuaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
       return $infolist
    ->schema([
        Section::make('Informasi Kejuaraan')
            ->description('Detail umum kejuaraan dan pendaftaran')
            ->columns(2)
            ->schema([

                /* ===============================
                 * KOLOM 1 (KIRI)
                 * =============================== */
                TextEntry::make('nama_kejuaraan')
                    ->label('Nama Kejuaraan')
                    ->weight('bold')
                    ->size(TextEntry\TextEntrySize::Medium)
                    ->icon('heroicon-o-trophy'),

                TextEntry::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->icon('heroicon-o-calendar')
                    ->date('d F Y'),

                TextEntry::make('lokasi')
                    ->label('Lokasi')
                    ->icon('heroicon-o-map-pin'),

                /* ===============================
                 * KOLOM 2 (KANAN)
                 * =============================== */
                TextEntry::make('grades')
                    ->label('Grade Kejuaraan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'nasional_A'        => 'Nasional A',
                        'nasional_B'        => 'Nasional B',
                        'daerah_A'          => 'Daerah A',
                        'daerah_B'          => 'Daerah B',
                        'tryout_antar_club' => 'Tryout Antar Club',
                        default             => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn ($state) => match ($state) {
                        'nasional_A'        => 'success',
                        'nasional_B'        => 'primary',
                        'daerah_A'          => 'warning',
                        'daerah_B'          => 'gray',
                        'tryout_antar_club' => 'info',
                        default             => 'secondary',
                    }),

                TextEntry::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->icon('heroicon-o-calendar-days')
                    ->date('d F Y'),

                TextEntry::make('slug')
                    ->label('Link Pendaftaran')
                    ->state(fn ($record) => route('kejuaraan.daftar', $record->slug))
                    ->url(fn ($state) => $state)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->icon('heroicon-o-link')
                    ->helperText('Bagikan link ini kepada peserta'),
            ]),
    ]);
    }
}
