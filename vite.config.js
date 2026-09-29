import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/adminlte.css',
                'resources/js/adminlte.js',
            ],
            refresh: true,
        }),
    ],
});
