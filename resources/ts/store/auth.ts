import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as null | Record<string, any>,
    loading: false,
    error: '',
    fatal: false,
  }),

  actions: {
    async fetchUser() {
      this.loading = true
      try {
        const response = await axios.get('/api/user')
        this.user = response.data
        this.error = ''
        this.fatal = false
      } catch (err: any) {
        this.user = null
        this.error = err.response?.data?.message || 'No autenticado'
        this.fatal = true
      } finally {
        this.loading = false
      }
    },

    async login(email: string, password: string) {
      this.loading = true
      try {
        await axios.get('/sanctum/csrf-cookie')
        const response = await axios.post('/api/login', { email, password })
        this.user = response.data
        this.error = ''
        this.fatal = false
        localStorage.setItem('auth_token', response.data.token)
      } catch (err: any) {
        this.error = err.response?.data?.message || 'Error de login'
        this.fatal = false
        throw err
      } finally {
        this.loading = false
      }
    },

    async logout() {
      await axios.post('/api/logout')
      localStorage.removeItem('auth_token')
      this.user = null
    },
  }
})
