<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn; 
use Filament\Tables\Columns\ImageColumn;




class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                         Forms\Components\FileUpload::make('image')
                            ->image()
                            ->nullable(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama unit')
                            
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('link')
                        ->label('Link website unit')
                            ->url()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat unit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->maxLength(65535),
                       
                    ]),
            ])->columns([
                'md' => 1,
                'md' => 2,
                'md' => 3,
                'xl' => 4,
                '2xl' => 5,         
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
           // fallback jika kosong

            TextColumn::make('name')
                ->label('Nama Unit')
                ->searchable()
                ->sortable()
                ->wrap(),

            TextColumn::make('link')
                ->label('Link Website')
                ->url(fn ($record) => $record->link, true)
                ->searchable()
                ->sortable()
                ->limit(30)
                ->wrap(),

            TextColumn::make('alamat')
                ->label('Alamat Unit')
                ->searchable()
                ->sortable()
                ->wrap()
                ->limit(50),
        ])
        ->filters([
            // Tambahkan filter jika diperlukan
        ])
        ->actions([
              Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
