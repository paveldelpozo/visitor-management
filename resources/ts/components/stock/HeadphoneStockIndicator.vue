<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { HeadphoneStock } from '@/types/visitor'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const stock = ref<HeadphoneStock>()

async function loadStock() {
    const { data, error, status } = await useApi('get', '/api/stock')

    if (error) {
        catchError('Ocurrió un error al intentar obtener el stock de auriculares.', error)
    } else {
        stock.value = data
    }
}

onMounted(loadStock)

defineExpose({ loadStock })
</script>

<template>
    <v-card color="info" class="pa-2">
        <v-card-title class="text-subtitle-1 text-md-h5">
            <v-row dense>
                <v-col cols="7" md="6" class="d-flex justify-md-start align-center">
                    <v-icon class="mr-2">mdi-headphones</v-icon>
                    <strong>Auriculares</strong>:
                    {{ stock?.used }} / {{ stock?.total }}
                </v-col>
                <v-col cols="5" md="6" class="d-flex justify-end align-center">
                    (<strong>Libres</strong>: {{ stock?.available }})
                </v-col>
            </v-row>
        </v-card-title>
    </v-card>
</template>
