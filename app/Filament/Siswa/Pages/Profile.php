<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Filament\Pages\Page;
use Filament\Forms;
use App\Helpers\NameHelper;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;



class Profile extends Page implements Forms\Contracts\HasForms
{


    private function resetKeyFields(callable $set): void
    {
        $set('nis', null);
        $set('nama_lengkap', null);
        $set('units_id', null);

        $this->lockKeyFields = false;
        $this->dataSaved     = false;
        $this->isEditing     = true;
    }


    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Profil Siswa';
    protected static string $view = 'filament.siswa.pages.profile';

    public ?array $data = [];
    public bool $isEditing = true;
    public bool $lockKeyFields = false;
    public bool $dataSaved = false;

    // model untuk header profil di Blade
    public ?Siswa $siswaModel = null;

    public function mount(): void
    {
        $this->loadSiswaDataToForm();
    }

    /**
     * Load data siswa ke state Livewire & form
     */
    private function loadSiswaDataToForm(): void
    {
        $user  = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        // simpan model untuk header
        $this->siswaModel = $siswa;

        $this->lockKeyFields = $siswa
            && !empty($siswa->nis)
            && !empty($siswa->nama_lengkap)
            && !empty($siswa->units_id);

        $this->dataSaved = $this->lockKeyFields;

        $formData = [
            'nama_lengkap' => $user->name,
            'nis'          => $siswa->nis ?? '',
            'units_id'     => $siswa->units_id ?? '',
        ];

        if ($siswa) {
            $formData = array_merge($formData, $siswa->toArray());

            // pastikan format tanggal cocok dengan DatePicker (Y-m-d)
            if ($siswa->tanggal_lahir) {
                $formData['tanggal_lahir'] = Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d');
            }
        }

        $this->data = $formData;
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Dasar & Akademik')
                    ->columns(2)
                    ->schema([
                        // Forms\Components\FileUpload::make('image')
                        //     ->avatar()
                        //     ->directory('profil_photos')
                        //     ->disk('public')
                        //     ->maxSize(1024)
                        //     ->imageEditor()
                        //     ->previewable(true)
                        //     ->columnSpanFull(),

                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->required()
                            ->disabled(fn() => !$this->isEditing || $this->lockKeyFields)
                            ->live(debounce: 500)
                            ->dehydrated()
                            ->afterStateUpdated(
                                fn($state, callable $set, $get) => $this->checkAndLoadData($set, $get)
                            ),

                        Forms\Components\TextInput::make('nama_lengkap')
                            ->required()
                            ->disabled(fn() => !$this->isEditing || $this->lockKeyFields)
                            ->dehydrated()
                            ->live(debounce: 500)
                            ->afterStateUpdated(
                                fn($state, callable $set, $get) => $this->checkAndLoadData($set, $get)
                            ),

                        Forms\Components\TextInput::make('no_register')
                            ->label('No Register')
                            ->required()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Select::make('units_id')
                            ->label('Unit')
                            ->options(Unit::pluck('name', 'id'))
                            ->required()
                            ->disabled(fn() => !$this->isEditing || $this->lockKeyFields)
                            ->live(debounce: 500)
                            ->dehydrated()
                            ->afterStateUpdated(
                                fn($state, callable $set, $get) => $this->checkAndLoadData($set, $get)
                            ),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->disabled(fn() => !$this->isEditing)
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y'),

                        Forms\Components\Select::make('current_belt_level')
                            ->options(self::beltOptions())
                            ->disabled(),

                        Forms\Components\Select::make('kelas_id')
                            ->options(Kelas::pluck('name', 'id'))
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Biodata')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->disabled(fn() => !$this->isEditing),
                        Forms\Components\Select::make('golongan_darah')
                            ->options([
                                'A'  => 'A',
                                'B'  => 'B',
                                'AB' => 'AB',
                                'O'  => 'O',
                            ])
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->disabled(fn() => !$this->isEditing),
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->rows(3)
                            ->disabled(fn() => !$this->isEditing)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Informasi Orang Tua')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')
                            ->disabled(fn() => !$this->isEditing),
                        Forms\Components\TextInput::make('pekerjaan_ayah')
                            ->disabled(fn() => !$this->isEditing),
                        Forms\Components\TextInput::make('nama_ibu')
                            ->disabled(fn() => !$this->isEditing),
                        Forms\Components\TextInput::make('pekerjaan_ibu')
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Lain-lain')
                    ->schema([
                        Forms\Components\TextInput::make('beladiri_yang_pernah_diikuti')
                            ->disabled(fn() => !$this->isEditing),
                    ]),
            ])
            ->statePath('data');
    }

    private function checkAndLoadData(callable $set, callable $get): void
    {
        $nis         = $get('nis');
        $namaLengkap = $get('nama_lengkap');
        $unitId      = $get('units_id');

        if (empty($nis) || empty($namaLengkap) || empty($unitId)) {
            return;
        }

        $compareNama = strtolower(
            trim(preg_replace('/\s+/', ' ', $namaLengkap))
        );

        $siswa = Siswa::where('nis', $nis)
            ->whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [$compareNama])
            ->where('units_id', $unitId)
            ->first();

        // ===============================
        // JIKA DATA ADA
        // ===============================
        if ($siswa) {

            // ❌ SUDAH DIPAKAI USER LAIN
            if ($siswa->user_id && $siswa->user_id !== Auth::id()) {

                $this->resetKeyFields($set);

                Notification::make()
                    ->title('❌ Data sudah digunakan')
                    ->body('NIS, Nama, dan Unit sudah dipakai user lain. Silakan isi data yang benar.')
                    ->danger()
                    ->send();

                return;
            }

            $this->loadSiswaData($set, $siswa);

            $this->lockKeyFields = true;

            Notification::make()
                ->title('✅ DATA COCOK!')
                ->body('Data siswa berhasil dimuat otomatis.')
                ->success()
                ->send();

            return;
        }

        $this->lockKeyFields = false;

        Notification::make()
            ->title('⚠️ Data tidak ditemukan')
            ->body('Silakan isi manual atau periksa kembali data.')
            ->warning()
            ->send();
    }


    private function loadSiswaData(callable $set, Siswa $siswa): void
    {
        $tanggalLahir = $siswa->tanggal_lahir
            ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')
            : null;

        $set('no_register', $siswa->no_register);
        $set('tempat_lahir', $siswa->tempat_lahir);
        $set('tanggal_lahir', $tanggalLahir);
        $set('jenis_kelamin', $siswa->jenis_kelamin);
        $set('kelas_id', $siswa->kelas_id);
        $set('current_belt_level', $siswa->current_belt_level);
        $set('golongan_darah', $siswa->golongan_darah);
        $set('alamat_lengkap', $siswa->alamat_lengkap);
        $set('no_telepon', $siswa->no_telepon);
        $set('nama_ayah', $siswa->nama_ayah);
        $set('pekerjaan_ayah', $siswa->pekerjaan_ayah);
        $set('nama_ibu', $siswa->nama_ibu);
        $set('pekerjaan_ibu', $siswa->pekerjaan_ibu);
        $set('beladiri_yang_pernah_diikuti', $siswa->beladiri_yang_pernah_diikuti);
    }

    
    public function save(): void
    {
        $user     = Auth::user();
        $formData = $this->form->getState();

        //\Log::info('SAVE FORM DATA:', $formData);

        if (
            empty($formData['nis']) ||
            empty($formData['nama_lengkap']) ||
            empty($formData['units_id'])
        ) {
            Notification::make()
                ->title('❌ Data wajib tidak lengkap!')
                ->danger()
                ->send();

            return;
        }


        $compareNama = strtolower(
            trim(
                preg_replace('/\s+/', ' ', $formData['nama_lengkap'])
            )
        );


        $existing = Siswa::where('nis', $formData['nis'])
            ->whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [$compareNama])
            ->where('units_id', $formData['units_id'])
            ->first();


        $formData['nama_lengkap'] = NameHelper::normalize($formData['nama_lengkap']);

        if ($existing) {
            // Jika sudah dipakai user lain → TOLAK
            if ($existing->user_id && $existing->user_id !== $user->id) {
                Notification::make()
                    ->title('❌ Data sudah digunakan user lain!')
                    ->danger()
                    ->send();

                return;
            }

            // Update field
            $existing->fill($formData);

            // Jika belum pernah di-link ke user
            if (!$existing->user_id) {
                $existing->user_id = $user->id;
            }

            $existing->save();

            // Sinkron nama user
            $user->update([
                'name' => $existing->nama_lengkap,
            ]);

            Notification::make()
                ->title('✅ Data berhasil diupdate')
                ->success()
                ->send();
        }
        // ===============================
        // 6. Jika data siswa BELUM ada
        // ===============================
        else {
            $new = Siswa::create(array_merge($formData, [
                'user_id' => $user->id,
                'status'  => 'Aktif',
            ]));

            $user->update([
                'name' => $new->nama_lengkap,
            ]);

            Notification::make()
                ->title('✅ Data siswa baru disimpan')
                ->success()
                ->send();
        }

        // ===============================
        // 7. Reload state & lock form
        // ===============================
        $this->loadSiswaDataToForm();
        $this->lockKeyFields = true;
        $this->dataSaved     = true;
        $this->isEditing     = false;
    }

    public static function beltOptions(): array
    {
        return [
            'putih'               => 'Putih',
            'kuning'              => 'Kuning',
            'kuning strip hijau'  => 'Kuning Strip Hijau',
            'hijau'               => 'Hijau',
            'hijau strip biru'    => 'Hijau Strip Biru',
            'biru'                => 'Biru',
            'biru strip merah'    => 'Biru Strip Merah',
            'merah'               => 'Merah',
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam'               => 'Hitam',
        ];
    }

    public function edit(): void
    {
        $this->isEditing = true;
    }
}
