<script setup lang="ts">
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

async function logout() {
    try {
        await axios.post('/api/logout')
        localStorage.removeItem('auth_token')
        router.push('/login').then()
    } catch (e) {
        console.error('Error al cerrar sesión:', e)
    }
}

console.log('DefaultLayout loaded')
</script>

<template>
    <v-app>
        <!-- Barra superior -->
        <v-app-bar app color="primary" dark>
            <v-toolbar-title>Visitor Manager</v-toolbar-title>
            <v-spacer />
            <v-btn text icon @click="logout" title="Cerrar sesión">
                <v-icon>mdi-logout</v-icon>
            </v-btn>
        </v-app-bar>

        <!-- Contenido principal -->
        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>
