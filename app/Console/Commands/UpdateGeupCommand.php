<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;

class UpdateGeupCommand extends Command
{
    protected $signature = 'siswa:update-geup';
    protected $description = 'Update kolom geup berdasarkan current_belt_level';

    public function handle()
    {
        $map = [
            'putih'              => 10,
            'kuning'             => 9,
            'kuning strip hijau' => 8,
            'hijau'              => 7,
            'hijau strip biru'   => 6,
            'biru'               => 5,
            'biru strip merah'   => 4,
            'merah'              => 3,
            'merah strip 1'      => 3,
            'merah strip 2'      => 2,
            'hitam'              => 1,
        ];

        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            $belt = strtolower($siswa->current_belt_level);
            $siswa->geup = $map[$belt] ?? null;
            $siswa->save();
        }

        $this->info('✅ Semua siswa sudah diupdate dengan nilai geup!');
    }
}
