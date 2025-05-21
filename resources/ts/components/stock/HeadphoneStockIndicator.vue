<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import type { HeadphoneStock } from '@/types/visitor'
import { echo } from '@/echo'

echo.channel('visitors')
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
    <v-card>
        <v-card-title class="text-h6">
            Auriculares: {{ stock?.used }} / {{ stock?.total }} (Libres: {{ stock?.available }})
        </v-card-title>
    </v-card>
</template>
