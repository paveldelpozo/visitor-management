<script setup lang="ts">
import {defineProps, defineEmits, ref} from 'vue'
import {useRouter} from "vue-router";
import QrDialog from '@/layouts/components/ui/QrDialog.vue';

const props = defineProps<{
    user: Record<string, any>
    isDark: boolean
}>()

const router = useRouter()

const qrRef = ref<typeof QrDialog>()

const emit = defineEmits(['toggle-theme', 'logout', 'toggle-drawer'])
</script>

<template>
    <v-app-bar app color="info">
        <v-app-bar-nav-icon @click="emit('toggle-drawer')" />

        <v-toolbar-title @click="router.push({ name: 'home' })" style="cursor: pointer" class="d-none d-md-flex align-center">
            Gestión de Auriculares
        </v-toolbar-title>

        <v-spacer />

        <v-btn variant="outlined" class="d-flex align-center" link :to="{ name: 'users.index', query: { user: user.id } }">
            <v-icon class="mr-2">mdi-account-circle</v-icon>
            <strong>{{ props.user?.name }}</strong>
        </v-btn>

        <v-btn icon @click="qrRef?.openDialog()" title="Mostrar QR">
            <v-icon>mdi-qrcode</v-icon>
        </v-btn>

        <v-btn icon @click="emit('toggle-theme')" :title="props.isDark ? 'Modo claro' : 'Modo oscuro'" class="mr-2">
            <v-icon>{{ props.isDark ? 'mdi-white-balance-sunny' : 'mdi-weather-night' }}</v-icon>
        </v-btn>

        <v-btn text icon @click="emit('logout')" title="Cerrar sesión">
            <v-icon>mdi-logout</v-icon>
        </v-btn>

        <QrDialog ref="qrRef" />
    </v-app-bar>
</template>
