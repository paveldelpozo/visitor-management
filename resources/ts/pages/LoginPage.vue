<template>
    <v-container>
        <h1>Iniciar sesión</h1>
        <v-form @submit.prevent="submitLogin">
            <v-text-field autocomplete="email" variant="solo" v-model="email" label="Email" />
            <v-text-field autocomplete="password" variant="solo" v-model="password" label="Contraseña" :type="type">
                <template #append-inner>
                    <v-icon @click="type = (type === 'password' ? 'text' : 'password')">{{ type === 'password' ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                </template>
            </v-text-field>
            <v-btn block size="large" type="submit" color="primary" :loading="auth.loading">Entrar</v-btn>
            <v-alert v-if="auth.error" type="error" class="mt-4">{{ auth.error }}</v-alert>
        </v-form>
    </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const type = ref('password')

async function submitLogin() {
    try {
        await auth.login(email.value, password.value)
        router.push('/')
    } catch {
        // el error ya se maneja en el store
    }
}
</script>
