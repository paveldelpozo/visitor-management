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
        <v-card-title class="text-h5">
            <v-row dense>
                <v-col cols="12" md="6" class="d-flex justify-md-start align-center">
                    <v-icon size="28" class="mr-2">mdi-headphones</v-icon>
                    <strong>Auriculares</strong>:
                    {{ stock?.used }} / {{ stock?.total }}
                </v-col>
                <v-col cols="12" md="6" class="d-flex justify-md-end align-center">
                    (<strong>Libres</strong>: {{ stock?.available }})
                </v-col>
            </v-row>
        </v-card-title>
    </v-card>
</template>
