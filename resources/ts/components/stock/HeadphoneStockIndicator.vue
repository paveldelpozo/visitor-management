<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import type { HeadphoneStock } from '@/types/visitor'

Echo.channel('visitors')
    .listen('.updated', (e: any) => {
        console.log('Actualización en tiempo real:', e.visitor)
        // Puedes emitir un evento, recargar datos, etc.
    })

const stock = ref<HeadphoneStock>()

async function loadStock() {
    const { data } = await axios.get('/api/stock')
    stock.value = data
}

onMounted(loadStock)
</script>

<template>
    <v-card color="blue-lighten-3" class="pa-2">
        <v-card-title class="text-h5">
            <v-icon size="28" class="mr-2">mdi-headphones</v-icon>
            <strong>Auriculares</strong>: {{ stock?.used }} / {{ stock?.total }} (<strong>Libres</strong>: {{ stock?.available }})
        </v-card-title>
    </v-card>
</template>
