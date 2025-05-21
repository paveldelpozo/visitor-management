import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

// @ts-ignore
window.Pusher = Pusher

export const echo = new Echo({
    broadcaster: 'pusher',
    // @ts-ignore
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    // @ts-ignore
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    enabledTransports: ['ws', 'wss'],
})
