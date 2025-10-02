<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-start gap-3">
            @if (!$isEditing)
                <x-filament::button color="primary" wire:click="edit">
                    Edit Profil
                </x-filament::button>
            @else
                <x-filament::button color="success" wire:click="save">
                    Simpan
                </x-filament::button>

                <x-filament::button color="secondary" wire:click="$set('isEditing', false)">
                    Batal
                </x-filament::button>
            @endif
        </div>

        {{-- Form Profil --}}
        {{ $this->form }}
    </div>
</x-filament-panels::page>
