<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Get;
use Filament\Tables\Actions\BulkActionGroup;



class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';

    public static function form(Form $form): Form
    {
        return $form
          
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),

                // ✅ Upload multiple images max 4
            FileUpload::make('images_path')
                    ->label('Foto (maks 4)')
                    ->multiple()
                    ->maxFiles(4)
                    ->disk('public')
                    ->directory('gallery')
                    ->image()
                    ->preserveFilenames()
            ]);
    }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('title')
                        ->label('Judul')
                        ->sortable()
                        ->searchable(),

                    Tables\Columns\TextColumn::make('description')
                        ->label('Deskripsi')
                        ->limit(50),

                    // ✅ tampilkan foto pertama saja di tabel
                    Tables\Columns\ImageColumn::make('images_path.0')
                    ->label('Foto Utama')
                    ->circular(),
                   
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

    


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
