<?php

namespace App\Filament\Pages;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Notifications\Notification;

class KuotaKejuaraanPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Kuota Kejuaraan';
    protected static ?string $navigationGroup = 'Data Kejuaraan';
    protected static string $view = 'filament.pages.kuota-kejuaraan-page';

    public string $activeTab = 'kelas';

    public function table(Table $table): Table
    {
        /*
        |----------------------------------------------------------------------
        | TAB 1 : KELAS
        |----------------------------------------------------------------------
        */
        if ($this->activeTab === 'kelas') {
            return $table
                ->query(Kelas::query())
                ->columns([

                    // ✅ FOTO KELAS
                   Tables\Columns\ImageColumn::make('image')
    ->label('Foto')
    ->disk('public')
    ->circular()
    ->defaultImageUrl(url('/images/default-kelas.png')),
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama Kelas')
                        ->sortable()
                        ->searchable(),

                    Tables\Columns\TextColumn::make('description')
                        ->label('Deskripsi')
                        ->limit(50),

                    Tables\Columns\TextColumn::make('kuota_awal')
                        ->label('Kuota Kelas')
                        ->sortable(),
                ])
                ->headerActions([
                    Tables\Actions\Action::make('addKelas')
                        ->label('Tambah Kelas')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->form([

                            // ✅ UPLOAD IMAGE
                           Forms\Components\FileUpload::make('image')
    ->label('Foto Kelas')
    ->image()
    ->disk('public')
    ->directory('kelas')
    ->visibility('public')
    ->imageEditor()
    ->maxSize(7168)
    ->nullable()
    ->preserveFilenames(false)
    ->columnSpanFull(),

                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kelas')
                                ->required(),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi'),

                            Forms\Components\TextInput::make('kuota_awal')
                                ->label('Kuota Awal')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function ($data) {
                            Kelas::create($data);

                            Notification::make()
                                ->title('Kelas berhasil ditambahkan')
                                ->success()
                                ->send();
                        }),
                ])
                ->actions([
                    Tables\Actions\EditAction::make()
                        ->form([

                            // ✅ EDIT IMAGE
                            Forms\Components\FileUpload::make('image')
                                ->label('Foto Kelas')
                                ->image()
                                ->disk('public')
                                ->directory('kelas')
                                ->imageEditor()
                                ->maxSize(2048)
                                ->nullable(),

                            Forms\Components\TextInput::make('name')
                                ->required(),

                            Forms\Components\Textarea::make('description'),

                            Forms\Components\TextInput::make('kuota_awal')
                                ->numeric()
                                ->required(),
                        ]),
                ]);
        }

        /*
        |----------------------------------------------------------------------
        | TAB 2 : SISWA
        |----------------------------------------------------------------------
        */
        return $table
            ->query(
                Siswa::query()
                    ->with('kelas')
                    ->withCount([
                        'kejuaraan as kejuaraan_count' => fn ($q) =>
                            $q->where('periode', now()->year),
                    ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->default('-'),

                Tables\Columns\TextColumn::make('kelas.kuota_awal')
                    ->label('Kuota Kelas'),

                Tables\Columns\TextColumn::make('kejuaraan_count')
                    ->label('Kejuaraan Diikuti (Tahun Ini)')
                    ->badge(),

                Tables\Columns\TextColumn::make('sisa_kuota')
                    ->label('Sisa Kuota')
                    ->getStateUsing(fn ($record) => $record->sisaKuota())
                    ->badge()
                    ->color(fn ($state) => $state === 0 ? 'danger' : 'success'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewKejuaraan')
                    ->label('Lihat Kejuaraan')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn ($record) =>
                        "Riwayat Kejuaraan: {$record->nama_lengkap}"
                    )
                    ->modalContent(fn ($record) =>
                        view('filament.siswa.pages.detail-kejuaraan', [
                            'kejuaraans' => $record->kejuaraan,
                        ])
                    )
                    ->modalSubmitAction(false),
            ]);
    }
}
