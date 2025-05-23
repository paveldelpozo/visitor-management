<script setup lang="ts">
import {ref, watch} from 'vue'
import { defineProps } from 'vue'

const props = defineProps<{ drawer: boolean }>()
const emit = defineEmits(['update:drawer'])
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
            <v-list-item link :to="{ name: 'logs' }">
                <template #prepend><v-icon>mdi-file-document-outline</v-icon></template>
                <v-list-item-title>Registro de acciones</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
