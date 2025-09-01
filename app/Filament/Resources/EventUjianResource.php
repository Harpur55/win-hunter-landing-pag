<?php
namespace App\Imports; // <--- PASTIKAN INI

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


use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventUjianExport;

class EventUjianResource extends Resource
{
    protected static ?string $model = EventUjian::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Ujian Kenaikan Tingkat'; // Untuk grup menu
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
         ])
            ->filters([
                // Filter event berdasarkan tanggal atau lokasi jika perlu
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(), // Untuk melihat detail event dan peserta
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
              ->headerActions([
            

            // Action::make('export_siswa')
            //     ->label('Ekspor ke Excel')
            //     ->color('success') // Warna hijau
            //     ->icon('heroicon-o-document-arrow-up') 
            //     ->action(fn () => Excel::download(new SiswaExport, 'data_siswa_' . date('Ymd_His') . '.xlsx')),
              ]);
    }
    

    public static function getRelations(): array
    {
        return [
            // Daftarkan Relation Manager untuk Siswa
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
