import axios from 'axios'
import axiosRetry from 'axios-retry'

// @ts-ignore
axiosRetry(axios, {
    retries: 3,
    retryDelay: (retryCount) => retryCount * 1000,
    retryCondition: (error) => !error.response || error.code === 'ECONNABORTED',
})

// @ts-ignore
window.axios = axios;

// @ts-ignore
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// @ts-ignore
window.axios.defaults.withCredentials = true
// @ts-ignore
window.axios.defaults.baseURL = 'http://localhost:8000'
