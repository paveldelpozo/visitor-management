<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const logs = ref<any[]>([])
const loading = ref(false)
const selected = ref<any | null>(null)

const headers = [
    { text: 'ID', value: 'id', nowrap: true },
    { text: 'Mensaje', value: 'message', nowrap: true },
    { text: 'Usuario', value: 'user', nowrap: true },
    { text: 'Fecha', value: 'created_at', nowrap: true },
    { text: 'Acciones', value: 'actions', nowrap: true, sortable: false },
]

onMounted(async () => {
    loading.value = true

    const { data, error, status } = await useApi('get', '/api/client-logs')
    if (error) {
        catchError('Ocurrió un error al intentar obtener los registros.', error)
    } else {
        logs.value = data
    }

    loading.value = false
})
</script>

<template>
    <v-container>
        <h2 class="text-h5 mb-4">Errores de Cliente</h2>
        <v-data-table :headers="headers" :items="logs" :loading="loading" class="elevation-1">
            <template #item.user="{ item }">
                {{ item.user?.email || 'Anónimo' }}
            </template>
            <template #item.actions="{ item }">
                <v-btn icon @click="selected = item">
                    <v-icon>mdi-eye</v-icon>
                </v-btn>
            </template>
        </v-data-table>

        <v-dialog v-model="selected" width="600">
            <v-card v-if="selected">
                <v-card-title>Error ID #{{ selected.id }}</v-card-title>
                <v-card-text>
                    <p><strong>Mensaje:</strong> {{ selected.message }}</p>
                    <p><strong>Info:</strong> {{ selected.info }}</p>
                    <p><strong>URL:</strong> {{ selected.url }}</p>
                    <p><strong>IP:</strong> {{ selected.ip }}</p>
                    <p><strong>Usuario:</strong> {{ selected.user?.email || 'Anónimo' }}</p>
                    <v-textarea placeholder="Stacktrace" v-model="selected.stack" readonly />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text @click="selected = null">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
