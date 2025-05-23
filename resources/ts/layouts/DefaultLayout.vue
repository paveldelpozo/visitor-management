<script setup lang="ts">
import RealtimeVisitorNotifier from '@/components/notifications/RealtimeVisitorNotifier.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { storeToRefs } from "pinia";

const { user } = storeToRefs(useAuthStore())

const auth = useAuthStore()
const router = useRouter()
const drawer = ref(false)
const snackbar = ref(false)
const message = ref('')

async function logout() {
    try {
        await auth.logout()
        message.value = 'Sesión cerrada correctamente'
        snackbar.value = true
        setTimeout(() => router.push('/login'), 1000)
    } catch (e) {
        console.error('Error al cerrar sesión:', e)
    }
}
</script>

<template>
    <v-app>
        <v-navigation-drawer v-if="user" v-model="drawer" app temporary>
            <v-list>
                <v-list-item link to="/">
                    <template #prepend><v-icon>mdi-home</v-icon></template>
                    <v-list-item-title>Inicio</v-list-item-title>
                </v-list-item>
                <v-list-item link to="/create">
                    <template #prepend><v-icon>mdi-account-plus</v-icon></template>
                    <v-list-item-title>Nuevo visitante</v-list-item-title>
                </v-list-item>
                <v-list-item link to="/logs">
                    <template #prepend><v-icon>mdi-file-document-outline</v-icon></template>
                    <v-list-item-title>Registro de acciones</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="primary" dark>
            <v-app-bar-nav-icon v-if="user" @click="drawer = !drawer" />
            <v-toolbar-title @click="router.push('/')" style="cursor: pointer">
                <VIcon>mdi-umbrella-beach</VIcon>
                SEonTheBeach | Gestión de Auriculares
            </v-toolbar-title>
            <v-spacer />
            <v-btn v-if="user" text icon @click="logout" title="Cerrar sesión">
                <v-icon>mdi-logout</v-icon>
            </v-btn>
        </v-app-bar>

        <v-main>
            <slot />
        </v-main>

        <v-snackbar v-model="snackbar" color="success" timeout="3000">
            {{ message }}
        </v-snackbar>
        <RealtimeVisitorNotifier />
    </v-app>
</template>
