import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { fileURLToPath } from 'url';
import os from 'os'

const lanIP = Object.values(os.networkInterfaces())
    .flat()
    .find((iface: any) => iface?.family === 'IPv4' && !iface.internal)?.address || '127.0.0.1'

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
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: `http://${lanIP}:5173`,
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/ts'),
        },
    },
});
