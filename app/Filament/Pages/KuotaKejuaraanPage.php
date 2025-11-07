<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Kelas;
use App\Models\KejuaraanSiswa;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class KuotaKejuaraanPage extends Page implements Tables\Contracts\HasTable, Forms\Contracts\HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Kuota Kejuaraan';
    protected static ?string $navigationGroup = 'Data Kejuaraan';
    protected static string $view = 'filament.pages.kuota-kejuaraan-page';

    public string $activeTab = 'kelas';

    /**
     * Saat tab diganti, langsung refresh tabel.
     */
    public function updatedActiveTab(string $value): void
    {
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        // TAB: KELAS
        if ($this->activeTab === 'kelas') {
            return $table
                ->query(Kelas::query())
                ->columns([
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama Kelas')
                        ->sortable()
                        ->searchable(),

                    Tables\Columns\TextColumn::make('description')
                        ->label('Deskripsi')
                        ->limit(50),

                    Tables\Columns\TextColumn::make('kuota_awal')
                        ->label('Kuota Awal')
                        ->sortable(),

                    Tables\Columns\TextColumn::make('kuota')
                        ->label('Kuota Saat Ini')
                        ->sortable(),
                ])
                ->headerActions([
                    // 🔁 Reset Kuota
                  Tables\Actions\Action::make('resetKuotaSiswa')
    ->label('🔁 Reset Kuota Siswa')
    ->icon('heroicon-o-arrow-path')
    ->color('danger')
    ->requiresConfirmation()
    ->modalHeading('Konfirmasi Reset Kuota')
    ->modalDescription('Semua siswa akan dikembalikan ke kuota awal sesuai kelas masing-masing. Data kejuaraan tetap aman.')
    ->action(function () {
        \App\Models\Siswa::with('kelas')->chunk(100, function ($siswas) {
            foreach ($siswas as $siswa) {
                if ($siswa->kelas && $siswa->kelas->kuota_awal !== null) {
                    $siswa->update(['sisa_kuota' => $siswa->kelas->kuota_awal]);
                }
            }
        });

        \Filament\Notifications\Notification::make()
            ->title('✅ Kuota semua siswa berhasil direset ke nilai awal.')
            ->success()
            ->send();
    }),


                    // ➕ Tambah Kelas
                    Tables\Actions\Action::make('addKelas')
                        ->label('Tambah Kelas')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->button()
                        ->form([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kelas')
                                ->required(),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(2),

                            Forms\Components\TextInput::make('kuota_awal')
                                ->label('Kuota Awal')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function ($data) {
                            // Kuota awal juga jadi kuota aktif saat kelas baru dibuat
                            $data['kuota'] = $data['kuota_awal'];

                            Kelas::create($data);

                            Notification::make()
                                ->title('Kelas berhasil ditambahkan!')
                                ->success()
                                ->send();

                            $this->dispatch('$refresh');
                        }),
                ])
                ->actions([
                    // ✏️ Edit Kelas
                    Tables\Actions\EditAction::make()
                        ->form([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kelas')
                                ->required(),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3),

                            Forms\Components\TextInput::make('kuota_awal')
                                ->label('Kuota Awal')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('kuota')
                                ->label('Kuota Saat Ini')
                                ->numeric()
                                ->required(),
                        ])
                        ->after(function () {
                            $this->dispatch('$refresh');
                        }),
                ]);
        }

        // TAB: KUOTA SISWA
        return $table
            ->query(
                KejuaraanSiswa::query()
                    ->with(['siswa.kelas', 'kejuaraan'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('siswa.kelas.name')
                    ->label('Kelas'),

                Tables\Columns\TextColumn::make('kejuaraan.nama_kejuaraan')
                    ->label('Kejuaraan'),

                Tables\Columns\TextColumn::make('kuota_awal')
                    ->label('Kuota Awal')
                    ->getStateUsing(fn($record) => $record->siswa?->kelas?->kuota_awal ?? 0),

                Tables\Columns\TextColumn::make('terpakai')
                    ->label('Terpakai')
                    ->getStateUsing(
                        fn($record) =>
                        KejuaraanSiswa::whereHas(
                            'siswa',
                            fn($q) =>
                            $q->where('kelas_id', $record->siswa?->kelas_id)
                        )->count()
                    ),

                Tables\Columns\TextColumn::make('sisa')
                    ->label('Sisa Kuota')
                    ->getStateUsing(
                        fn($record) =>
                        max(0, ($record->siswa?->kelas?->kuota_awal ?? 0)
                            - KejuaraanSiswa::whereHas(
                                'siswa',
                                fn($q) =>
                                $q->where('kelas_id', $record->siswa?->kelas_id)
                            )->count())
                    ),
            ]);
    }
}
