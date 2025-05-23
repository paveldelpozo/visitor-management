<script setup lang="ts">
import { storeToRefs } from "pinia";
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePersistentTheme } from '@/composables/useTheme'
import RealtimeVisitorNotifier from '@/components/notifications/RealtimeVisitorNotifier.vue'
import AppDrawer from "@/layouts/components/ui/AppDrawer.vue";
import AppHeader from "@/layouts/components/ui/AppHeader.vue";
import vuetify from "@/plugins/vuetify";
import { useAuthStore } from '@/store/auth'

const { isDark, toggleTheme } = usePersistentTheme()
const { user } = storeToRefs(useAuthStore())

const auth = useAuthStore()
const router = useRouter()
const drawer = ref(!vuetify.display.mobile)
const snackbar = ref(false)
const message = ref('')

async function logout() {
    try {
        await auth.logout()
        message.value = 'Sesión cerrada correctamente'
        snackbar.value = true
        setTimeout(() => router.push({ name: 'login' }), 1000)
    } catch (e) {
        console.error('Error al cerrar sesión:', e)
    }
}
</script>

<template>
    <v-app>
        <AppDrawer v-if="user" v-model:drawer="drawer" />

        <AppHeader
            v-if="user"
            :user="user"
            :is-dark="isDark"
            @toggle-theme="toggleTheme"
            @logout="logout"
            @toggle-drawer="() => drawer = !drawer"
        />

        <v-main>
            <slot />
        </v-main>

        <v-snackbar v-model="snackbar" color="success" timeout="3000">
            {{ message }}
        </v-snackbar>

        <v-footer app color="blue-darken-2" height="40" class="d-flex justify-center">
            <small>&copy; {{ new Date().getFullYear() }} Pavel del Pozo – Todos los derechos reservados</small>
        </v-footer>

        <RealtimeVisitorNotifier />
    </v-app>
</template>
