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
use Illuminate\Database\Eloquent\Builder;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    /**
     * ✅ Filter hanya gallery Aktif (ADMIN FILAMENT)
     */
   public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->where('status', 'aktif');
}

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3),

          Forms\Components\Select::make('status')
    ->label('Status')
    ->options([
        'aktif'     => 'Aktif',
        'non-aktif' => 'Tidak Aktif',
    ])
    ->default('aktif')
    ->required(),

            FileUpload::make('images_path')
                ->label('Foto (maks 6)')
                ->multiple()
                ->maxFiles(6)
                ->disk('public')
                ->directory('gallery')
                ->image()
                ->preserveFilenames()
                ->required(),
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

                 Tables\Columns\TextColumn::make('status')
    ->label('Status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'aktif' => 'success',
        'non-aktif' => 'danger',
        default => 'gray',
    })
    ->sortable(),

                Tables\Columns\ImageColumn::make('images_path')
                    ->label('Foto Utama')
                    ->disk('public')
                    ->getStateUsing(fn($record) => $record->images_path[0] ?? null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit'   => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
