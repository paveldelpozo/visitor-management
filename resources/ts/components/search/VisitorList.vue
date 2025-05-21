<script setup lang="ts">
import type { Visitor } from '@/types/visitor'
import { echo } from '@/echo'

echo.channel('visitors')
    .listen('.updated', (e: any) => {
        console.log('Actualización en tiempo real:', e.visitor)
        // Puedes emitir un evento, recargar datos, etc.
    })

defineProps<{ visitors: Visitor[] }>()
defineEmits(['select'])
</script>

<template>
    <v-list>
        <v-list-item
            v-for="v in visitors"
            :key="v.id"
            @click="() => emit('select', v)"
        >
            <v-list-item-title>{{ v.name }} {{ v.surname }}</v-list-item-title>
            <v-list-item-subtitle>{{ v.phone }}</v-list-item-subtitle>
        </v-list-item>
    </v-list>
</template>
