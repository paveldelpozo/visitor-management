import Echo from 'laravel-echo'

import Pusher from 'pusher-js';

declare global {
    interface Window {
        // @ts-ignore
        Echo: Echo
        Pusher: typeof Pusher
    }
}

window.Pusher = Pusher
export const pusher = window.Pusher

window.Echo = new Echo({
    broadcaster: 'reverb',
    // host: window.location.hostname + ':6001',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
})
export const echo = window.Echo
