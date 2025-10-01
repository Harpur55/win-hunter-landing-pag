<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Models\Siswa;

/**
 * Halaman Profil Siswa
 */
class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title = 'Profil Siswa';
    protected static string $view = 'filament.siswa.pages.profile';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        if ($siswa) {
            $this->form->fill($siswa->toArray());
        } else {
            $this->form->fill([
                'nama_lengkap' => $user->name,
                'email'        => $user->email,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // isi form kamu di sini (sama seperti sebelumnya)...
                Forms\Components\Section::make('Informasi Pribadi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->disabled()
                            ->dehydrated(false), // tidak ikut update

                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->maxLength(3)
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Data Orang Tua')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Sekolah & Unit')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'nama_kelas')
                            ->searchable()
                            ->preload()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Select::make('units_id')
                            ->label('Unit Latihan')
                            ->relationship('unit', 'nama_unit')
                            ->searchable()
                            ->preload()
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Lain-lain')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('beladiri_yang_pernah_diikuti')
                            ->label('Beladiri yang Pernah Diikuti')
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Foto Profil')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Profil')
                            ->image()
                            ->imageEditor()
                            ->directory('siswa')
                            ->previewable(true)
                            ->disabled(fn() => !$this->isEditing),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label(fn() => $this->isEditing ? 'Batal' : 'Edit Profil')
                ->icon(fn() => $this->isEditing ? 'heroicon-o-x-mark' : 'heroicon-o-pencil-square')
                ->color(fn() => $this->isEditing ? 'danger' : 'primary')
                ->action(fn() => $this->isEditing = !$this->isEditing),

            Action::make('save')
                ->label('Simpan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn() => $this->isEditing)
                ->action(function () {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    $data = $this->form->getState();

                    $allowed = collect($data)->only([
                        'nama_lengkap',
                        'jenis_kelamin',
                        'tempat_lahir',
                        'tanggal_lahir',
                        'golongan_darah',
                        'alamat_lengkap',
                        'no_telepon',
                        'nama_ayah',
                        'pekerjaan_ayah',
                        'nama_ibu',
                        'pekerjaan_ibu',
                        'beladiri_yang_pernah_diikuti',
                        'image',
                        'kelas_id',
                        'units_id',
                    ])->toArray();

                    // Update atau buat siswa baru
                    $user->siswa()->updateOrCreate(
                        ['user_id' => $user->id],
                        $allowed
                    );

                    if (!empty($allowed['nama_lengkap'])) {
                        $user->update(['name' => $allowed['nama_lengkap']]);
                    }

                    $this->isEditing = false;

                    Notification::make()
                        ->title('Berhasil')
                        ->success()
                        ->body('Profil berhasil diperbarui.')
                        ->send();
                }),
        ];
    }
}
