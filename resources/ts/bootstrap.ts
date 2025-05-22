import axios from 'axios';

// @ts-ignore
window.axios = axios;

// Establecer baseURL de la API (ajústalo si usas proxy o puerto diferente)
axios.defaults.baseURL = 'http://localhost:8000'

// Permitir el envío de cookies para sesiones Sanctum
axios.defaults.withCredentials = true


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('auth_token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export default axios

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
