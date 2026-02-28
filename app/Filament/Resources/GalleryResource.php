<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';
    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

   

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Gallery')
                ->icon('heroicon-o-information-circle')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'aktif'     => 'Aktif',
                            'non-aktif' => 'Tidak Aktif',
                        ])
                        ->default('aktif')
                        ->required()
                        ->native(false),
                ]),

            Forms\Components\Section::make('Upload Foto')
                ->icon('heroicon-o-photo')
                ->description('Maksimal 6 foto. Otomatis dikonversi ke WebP.')
                ->schema([

                    Forms\Components\FileUpload::make('images_path')
                        ->label('')
                        ->multiple()
                        ->maxFiles(6)
                        ->reorderable()
                        ->panelLayout('grid')
                        ->image()
                        ->imagePreviewHeight('200')
                        ->loadingIndicatorPosition('center')
                        ->storeFiles(false)
                        ->required()
                        ->afterStateUpdated(function ($state, callable $set) {

                            $manager = new ImageManager(new Driver());
                            $paths = [];

                            foreach ($state as $file) {

                                if (! $file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                    $paths[] = $file;
                                    continue;
                                }

                                $fileName = Str::uuid() . '.webp';

                                $image = $manager
                                    ->read($file->getRealPath())
                                    ->scaleDown(width: 1920)
                                    ->toWebp(80);

                                Storage::disk('public')->put(
                                    'gallery/' . $fileName,
                                    $image->toString()
                                );

                                $paths[] = 'gallery/' . $fileName;
                            }

                            $set('images_path', $paths);
                        }),
                ]),
        ]);
    }

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('images_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(60)
                    ->getStateUsing(fn($record) => $record->images_path[0] ?? null),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('images_path')
                    ->label('Total Foto')
                    ->badge()
                    ->getStateUsing(fn($record) => count($record->images_path ?? []))
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match ($state) {
                        'aktif'     => 'success',
                        'non-aktif' => 'gray',
                        default     => 'warning',
                    }),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'aktif'     => 'Aktif',
                        'non-aktif' => 'Tidak Aktif',
                    ]),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
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