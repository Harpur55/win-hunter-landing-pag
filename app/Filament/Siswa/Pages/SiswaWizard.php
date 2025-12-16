<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SiswaWizard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $title = 'Lengkapi Data Siswa';
    protected static ?string $slug = 'siswa-wizard';

    protected static string $view = 'filament.siswa.pages.siswa-wizard';

    public ?Siswa $siswaData = null;
    public array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        // Jika wizard sudah selesai → langsung dashboard
        if ($user->needs_wizard === false) {
            redirect()->route('filament.siswa.pages.dashboard');
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    $this->stepValidasi(),
                    $this->stepLengkapi(),
                    $this->stepKonfirmasi(),
                ])
                    ->skippable(false)
                    ->submitAction('Selesai')
                    ->afterSubmit(fn () => $this->submitWizard()),
            ])
            ->statePath('data');
    }

    // ---------------------------------------------------------
    // STEP 1 — VALIDASI DATA
    // ---------------------------------------------------------
    private function stepValidasi()
    {
        return Forms\Components\Wizard\Step::make('Validasi Data')
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')->required(),
                Forms\Components\TextInput::make('no_register')->required(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('cek')
                        ->label('Cek Data Siswa')
                        ->color('primary')
                        ->action(function (array $data) {

                            $siswa = Siswa::where('nama_lengkap', $data['nama_lengkap'])
                                ->where('tanggal_lahir', $data['tanggal_lahir'])
                                ->where('no_register', $data['no_register'])
                                ->first();

                            if ($siswa) {
                                $this->siswaData = $siswa;

                                // Auto-fill
                                $this->form->fill([
                                    ...$this->form->getState(),
                                    'jenis_kelamin'  => $siswa->jenis_kelamin,
                                    'tempat_lahir'   => $siswa->tempat_lahir,
                                    'alamat_lengkap' => $siswa->alamat_lengkap,
                                    'no_telepon'     => $siswa->no_telepon,
                                    'nama_ayah'      => $siswa->nama_ayah,
                                    'nama_ibu'       => $siswa->nama_ibu,
                                ]);

                                $this->dispatch('notify', message: 'Data ditemukan, otomatis terisi.');
                            } else {
                                $this->dispatch('notify', message: 'Data tidak ditemukan. Isi manual.');
                            }
                        }),
                ]),
            ]);
    }

    // ---------------------------------------------------------
    // STEP 2 — LENGKAPI DATA
    // ---------------------------------------------------------
    private function stepLengkapi()
    {
        return Forms\Components\Wizard\Step::make('Lengkapi Data')
            ->schema([
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('tempat_lahir'),
                Forms\Components\Textarea::make('alamat_lengkap'),
                Forms\Components\TextInput::make('no_telepon'),
                Forms\Components\TextInput::make('nama_ayah'),
                Forms\Components\TextInput::make('nama_ibu'),
            ]);
    }

    // ---------------------------------------------------------
    // STEP 3 — KONFIRMASI
    // ---------------------------------------------------------
    private function stepKonfirmasi()
    {
        return Forms\Components\Wizard\Step::make('Konfirmasi')
            ->schema([
                Forms\Components\Placeholder::make('info')
                    ->content('Periksa kembali data sebelum menyelesaikan wizard.'),
            ]);
    }

    // ---------------------------------------------------------
    // FINAL SUBMIT (SETELAH WIZARD SELESAI)
    // ---------------------------------------------------------
    private function submitWizard()
    {
        $user = Auth::user();
        $data = $this->form->getState();

        if ($this->siswaData) {
            // Update data siswa lama
            $this->siswaData->update($data);
        } else {
            // Register siswa baru (aman meskipun namanya cocok)
            Siswa::create([
                ...$data,
                'nis' => 'WH-' . time(),
                'user_id' => $user->id,
            ]);
        }

        // Wizard selesai → tandai sekali saja
        $user->update(['needs_wizard' => false]);

        return redirect()->route('filament.siswa.pages.dashboard');
    }
}
