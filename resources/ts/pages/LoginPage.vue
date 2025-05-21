<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

async function login() {
    error.value = ''
    try {
        await axios.get('/sanctum/csrf-cookie')
        const response = await axios.post('/api/login', { email: email.value, password: password.value })
        localStorage.setItem('auth_token', '1') // solo para mantener lógica existente
        router.push('/')
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error desconocido'
    }
}
</script>

<template>
    <v-form @submit.prevent="login">
        <v-text-field v-model="email" label="Email" />
        <v-text-field v-model="password" label="Contraseña" type="password" />
        <v-btn type="submit" color="primary">Entrar</v-btn>
        <v-alert v-if="error" type="error">{{ error }}</v-alert>
    </v-form>
</template>
