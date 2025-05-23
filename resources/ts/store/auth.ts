import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useApi } from '@/composables/useApi'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<Record<string, any> | null>(null)
    const loading = ref(false)
    const error = ref('')
    const fatal = ref(false)

    const fetchUser = async () => {
        loading.value = true
        const { data, error: err } = await useApi('get', '/api/user')

        if (data) {
            user.value = data
            error.value = ''
            fatal.value = false
        } else {
            user.value = null
            error.value = err || 'No autenticado'
            fatal.value = true
        }

        loading.value = false
    }

    const login = async (email: string, password: string) => {
        loading.value = true
        error.value = ''
        fatal.value = false

        try {
            await useApi('get', '/sanctum/csrf-cookie')
            const { data, error: err } = await useApi('post', '/api/login', { email, password })

            if (data) {
                user.value = data
                localStorage.setItem('auth_token', data.token)
            } else {
                error.value = err || 'Error de login'
                throw new Error(error.value)
            }
        } finally {
            loading.value = false
        }
    }

    const logout = async () => {
        await useApi('post', '/api/logout')
        localStorage.removeItem('auth_token')
        user.value = null
    }

    return {
        user,
        loading,
        error,
        fatal,
        fetchUser,
        login,
        logout,
    }
})
