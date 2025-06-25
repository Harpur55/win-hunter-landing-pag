<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachResource\Pages;
use App\Filament\Resources\CoachResource\RelationManagers;

use App\Models\Coach;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class CoachResource extends Resource
{
    protected static ?string $model = Coach::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
      protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            Section::make('Informasi Dasar Profil')
                ->description('Detail pribadi dan peran dalam organisasi.')
                ->columns(3) // Menggunakan 3 kolom untuk tata letak yang fleksibel
                ->schema([
                    // Kolom untuk Foto (memakan 1 kolom)
                    FileUpload::make('foto') // Nama field 'foto'
                        ->label('Foto Profil')
                        ->image()
                        ->imagePreviewHeight('200')
                        ->directory('profil_photos') 
                        ->avatar() 
                        ->nullable() // Foto bisa kosong
                        ->columnSpan(1), // Memakan 1 kolom

                    // Grid untuk detail teks (memakan 2 kolom sisa dari Section)
                    Grid::make(2) // Grid internal dengan 2 kolom
                        ->columnSpan(2) // Memakan 2 kolom dari Section induk
                        ->schema([
                            TextInput::make('nama') // Nama field 'nama'
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Masukkan nama lengkap'),

                            Select::make('role') // Nama field 'role'
                                ->label('Peran')
                                ->options([
                                    'Pelatih' => 'Pelatih',
                                    'Asisten Pelatih' => 'Asisten Pelatih',
                                    'Pengurus' => 'Pengurus',
                                    'Administrator' => 'Administrator',
                                ])
                                ->required()
                                ->native(false) // Tampilan yang lebih modern
                                ->placeholder('Pilih peran'),

                            TextInput::make('Sabuk') // Nama field 'Sabuk' (pastikan case sesuai DB)
                                ->label('Tingkatan Sabuk')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Hitam Dan I'),
                            
                            Select::make('status') // Nama field 'status'
                                ->label('Status')
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Tidak Aktif' => 'Tidak Aktif',
                                    'Cuti' => 'Cuti',
                                ])
                                ->required()
                                ->default('Aktif') // Nilai default 'Aktif'
                                ->native(false),
                        ]),
                ]),

            Section::make('Detail Kontak')
                ->description('Informasi kontak dan alamat.')
                ->columns(1) // Menggunakan 1 kolom penuh
                ->schema([
                    TextInput::make('nomor_telepon') // Nama field 'nomor_telepon'
                        ->label('Nomor Telepon')
                        ->tel() // Tipe input telepon
                        ->nullable() // Bisa kosong
                        ->maxLength(20)
                        ->placeholder('Contoh: 081234567890'),

                    Textarea::make('alamat') // Nama field 'alamat'
                        ->label('Alamat Lengkap')
                        ->rows(3) // Tinggi textarea
                        ->nullable() // Bisa kosong
                        ->placeholder('Masukkan alamat lengkap Anda'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoaches::route('/'),
            'create' => Pages\CreateCoach::route('/create'),
            'edit' => Pages\EditCoach::route('/{record}/edit'),
        ];
    }
}
