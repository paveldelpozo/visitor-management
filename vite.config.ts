import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { fileURLToPath } from 'url';

// @ts-ignore
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
    ],
    server: {
        cors: true,
        host: 'localhost',
        port: 5173,
        strictPort: true,
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/ts'),
        },
    },
});
