import axios from 'axios'

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_APP_URL,
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
})

axiosInstance.defaults.withCredentials = true

axiosInstance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('auth_token')
if (token) {
    axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

// @ts-ignore
window.axios = axiosInstance;

export default axiosInstance
