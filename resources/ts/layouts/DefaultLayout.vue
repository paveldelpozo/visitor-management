<script setup lang="ts">
import { storeToRefs } from "pinia";
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePersistentTheme } from '@/composables/useTheme'
import { useOnlineStatus } from '@/composables/useOnlineStatus'
import RealtimeVisitorNotifier from '@/components/notifications/RealtimeVisitorNotifier.vue'
import AppDrawer from "@/layouts/components/ui/AppDrawer.vue";
import AppHeader from "@/layouts/components/ui/AppHeader.vue";
import vuetify from "@/plugins/vuetify";
import { useAuthStore } from '@/store/auth'

const { user } = storeToRefs(useAuthStore())

const { isDark, toggleTheme } = usePersistentTheme()
const { isOnline } = useOnlineStatus()

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
        setTimeout(() => router.push({ name: 'login' }), 50)
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

        <v-footer app color="blue-darken-2" height="52" class="d-flex pb-6 pb-md-4 justify-center">
            <small>&copy; {{ new Date().getFullYear() }} Pavel del Pozo – Todos los derechos reservados</small>
        </v-footer>

        <RealtimeVisitorNotifier />

        <VDialog :model-value="!isOnline" wide="400" max-width="600">
            <v-card color="error">
                <v-card-title>
                    <v-icon size="x-small">mdi-alert</v-icon>
                    Alerta
                </v-card-title>
                <v-divider />
                <v-card-text class="d-flex flex-column align-center justify-center">
                    <p class="text-h5 text-center"><strong>Estás sin conexión.</strong></p>
                    <v-icon size="128" class="mb-8 opacity-50">mdi-signal-off</v-icon>
                    <p class="text-h6 text-center">Algunos datos pueden no estar disponibles.</p>
                </v-card-text>
            </v-card>
        </VDialog>
    </v-app>
</template>
