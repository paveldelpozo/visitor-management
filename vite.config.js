import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/ts'),
        },
    },
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        origin: 'http://localhost:5173',
        cors: {
            origin: 'http://localhost:8000',
            credentials: true,
        }
    },
    test: {
        globals: true,
        environment: 'jsdom',
        setupFiles: './vitest.setup.ts'
    }
});
