import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                // Tambahkan semua panel di sini
                // 'resources/css/filament/admin/theme.css',
                'resources/css/filament/siswa/theme.css',
                // 'resources/js/filament/siswa/theme.js',
            ],
            refresh: true,
        }),
    ],
})
