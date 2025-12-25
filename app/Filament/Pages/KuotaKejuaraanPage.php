<?php

namespace App\Filament\Pages;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
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

    public function updatedActiveTab(string $value): void
    {
        // Saat tab berpindah, sinkronkan kuota siswa dulu
        if ($value === 'siswa') {
            $this->sinkronKuotaSiswa();
        }
        $this->dispatch('$refresh');
    }

    /**
     * 🔁 Sinkronkan semua sisa kuota siswa
     */
    protected function sinkronKuotaSiswa(): void
    {
        Siswa::with(['kelas', 'kejuaraan'])->chunk(100, function ($siswas) {
            foreach ($siswas as $siswa) {
                $siswa->updateQuietly([
                    'sisa_kuota' => $siswa->sisaKuota(),
                ]);
            }
        });
    }

    public function table(Table $table): Table
    {
        // =============================
        // TAB 1: KUOTA PER KELAS
        // =============================
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
                    // 🔁 RESET KUOTA SEMUA SISWA
              Tables\Actions\Action::make('resetKuotaSiswa')
    ->label('🔁 Reset Kuota Semua Siswa')
    ->icon('heroicon-o-arrow-path')
    ->color('danger')
    ->requiresConfirmation()
    ->modalHeading('Konfirmasi Reset Kuota')
    ->modalDescription('Semua siswa akan dikembalikan ke kuota awal sesuai kelas masing-masing.')
    ->action(function () {
        \App\Models\Siswa::with('kelas')->chunk(100, fn($siswas) =>
            $siswas->each->resetKuota()
        );

        \Filament\Notifications\Notification::make()
            ->title('✅ Kuota semua siswa berhasil direset.')
            ->success()
            ->send();
    }),

                    // ➕ TAMBAH KELAS
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
                    // ✏️ EDIT KELAS
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
                        ->after(function ($record) {
                            // 🔄 Setelah edit kuota_awal, update semua siswa di kelas ini
                            Siswa::where('kelas_id', $record->id)
                                ->with('kejuaraan')
                                ->get()
                                ->each(function ($siswa) {
                                    $siswa->syncSisaKuota();
                                });

                            Notification::make()
                                ->title('✅ Kuota siswa di kelas ini berhasil disinkronkan.')
                                ->success()
                                ->send();

                            $this->dispatch('$refresh');
                        }),
                ]);
        }

        // =============================
        // TAB 2: MONITORING SISWA
        // =============================
        return $table
            ->query(
                Siswa::query()
                    ->with(['kelas'])
                    ->withCount('kejuaraan')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->getStateUsing(fn($record) => $record->kelas?->name ?? '-'),

                Tables\Columns\TextColumn::make('kelas.kuota_awal')
                    ->label('Kuota Awal')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kejuaraan_count')
                    ->label('Kejuaraan Diikuti')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sisa_kuota')
                    ->label('Sisa Kuota')
                    ->getStateUsing(fn ($record) => $record->sisaKuota())
                    ->badge()
                    ->color(fn ($state) => (int)$state === 0 ? 'danger' : 'success')
                    ->sortable(),
            ])
            ->actions([
             
                Tables\Actions\Action::make('viewKejuaraan')
    ->label('Lihat Kejuaraan')
    ->icon('heroicon-o-eye')
    ->color('info')
    ->modalHeading(fn ($record) => "Riwayat Kejuaraan: {$record->nama_lengkap}")
    ->modalDescription('Berikut daftar kejuaraan yang pernah diikuti siswa ini.')
    ->modalContent(function ($record) {
        // Ambil data dari relasi belongsToMany
        $kejuaraans = $record->kejuaraan()
            ->withPivot([
                'nama_lengkap',
                'kategori_pertandingan',
                'kategori_atlit',
                'medali',
                'kelas_berat',
                'status',
            ])
            ->get();

        return view('filament.siswa.pages.detail-kejuaraan', compact('kejuaraans'));
    })
    ->modalSubmitAction(false)
    ->modalCancelActionLabel('Tutup')
    ->modalWidth('4xl'),
            ]);
            
    }
}
