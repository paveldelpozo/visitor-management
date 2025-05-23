<script setup lang="ts">
import {ref, watch} from 'vue'
import { defineProps } from 'vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps<{ drawer: boolean }>()
const emit = defineEmits(['update:drawer'])

const { isAdmin } = usePermissions()

const drawerValue = ref(props.drawer)

watch(() => props.drawer, (value: any) => drawerValue.value = value)
watch(drawerValue, (value: any) => emit('update:drawer', value))
</script>

<template>
    <v-navigation-drawer
        v-model="drawerValue"
        app
        :permanent="!$vuetify.display.mobile"
        :temporary="$vuetify.display.mobile"
        :location="$vuetify.display.mobile ? 'bottom' : undefined"
    >
        <h1 class="px-2 py-2 d-flex align-center bg-info dark elevation-4" style="height: 64px;">
            <v-icon size="small" color="white" class="mr-2">mdi-umbrella-beach</v-icon>
            SEonTheBeach
        </h1>

        <v-list color="info">
            <v-list-item link :to="{ name: 'home' }">
                <template #prepend><v-icon>mdi-home</v-icon></template>
                <v-list-item-title>Inicio</v-list-item-title>
            </v-list-item>
            <v-list-item :to="{ name: 'visitor.index' }">
                <template #prepend><v-icon>mdi-account-multiple</v-icon></template>
                <v-list-item-title>Asistentes</v-list-item-title>
            </v-list-item>
            <v-list-item link :to="{ name: 'visitor.create' }">
                <template #prepend><v-icon>mdi-account-plus</v-icon></template>
                <v-list-item-title>Nuevo asistente</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="isAdmin" link :to="{ name: 'users.index' }">
                <template #prepend><v-icon>mdi-account-cog</v-icon></template>
                <v-list-item-title>Usuarios</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="isAdmin" link :to="{ name: 'logs' }">
                <template #prepend><v-icon>mdi-file-document-outline</v-icon></template>
                <v-list-item-title>Registro de acciones</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
