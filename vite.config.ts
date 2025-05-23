import os from 'os'
import path from 'path';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa'
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

const lanIP = Object.values(os.networkInterfaces())
    .flat()
    .find((iface: any) => iface?.family === 'IPv4' && !iface.internal)?.address || '127.0.0.1'

// @ts-ignore
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
    build: {
        rollupOptions: {
            input: '/resources/ts/app.ts',
        },
    },
    plugins: [
        vue(),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'robots.txt'],
            manifest: {
                name: 'Gestión de Auriculares',
                short_name: 'Auriculares',
                description: 'App para gestionar visitantes y auriculares',
                theme_color: '#1976d2',
                background_color: '#ffffff',
                display: 'standalone',
                start_url: '/',
                icons: [
                    {
                        src: 'pwa-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: 'pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            },
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,vue}'],
                runtimeCaching: [
                    {
                        urlPattern: ({ request }) => request.mode === 'navigate',
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages'
                        }
                    }
                ]
            }
        }),
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
