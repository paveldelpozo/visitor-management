<script setup lang="ts">
import {ref, onMounted, watch} from 'vue'
import { useRoute, useRouter } from "vue-router";
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const route = useRoute()
const router = useRouter()

const logs = ref([])
const loading = ref(true)
const filters = ref({
    user: route.query.user || '',
    date: route.query.date || '',
})

const headers = [
    { title: 'Usuario', value: 'user' },
    { title: 'Asistente', value: 'visitor' },
    { title: 'Acción', value: 'action' },
    { title: 'Fecha', value: 'created_at' },
    { title: 'Meta', value: 'meta' },
]

const getParams = () => {
    const params: any = {}

    if (filters.value.user)
        params.user = filters.value.user

    if (filters.value.date)
        params.date = filters.value.date

    return params
}

async function fetchLogs() {
    loading.value = true

    const queryString = new URLSearchParams(getParams()).toString()

    const { data, error, status } = await useApi('get', `/api/logs?${queryString}`)

    if (error) {
        catchError('Ocurrió un error al intentar obtener los registros.', error)
    } else {
        logs.value = data.data
    }

    loading.value = false
}

function formatMeta(meta: any) {
    return JSON.stringify(meta, null, 2)
}

watch(filters, () => {
    router.push({ query: getParams() })
}, { deep: true })

onMounted(fetchLogs)
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <h1 class="text-h5 mb-4">
                    <v-icon class="mr-2" color="info">mdi-file-document-outline</v-icon>
                    Registro de acciones
                </h1>

                <v-row class="mb-4" dense>
                    <v-col cols="12" md="5">
                        <v-text-field
                            v-model="filters.user"
                            variant="solo"
                            label="Filtrar por usuario"
                            clearable
                            prepend-inner-icon="mdi-account"
                            @keyup.enter="fetchLogs"
                            @click:clear="fetchLogs"
                        />
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-text-field
                            v-model="filters.date"
                            variant="solo"
                            label="Filtrar por fecha (YYYY-MM-DD)"
                            clearable
                            prepend-inner-icon="mdi-calendar"
                            @keyup.enter="fetchLogs"
                            @click:clear="fetchLogs"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-btn @click="fetchLogs" block color="info" class="mt-2" size="large">
                            <v-icon>mdi-filter</v-icon>
                            Aplicar filtros
                        </v-btn>
                    </v-col>
                </v-row>

                <v-data-table
                    :headers="headers"
                    :items="logs"
                    :items-per-page="25"
                    :loading="loading"
                    class="elevation-1"
                    @click:row="(event: any, item: any) => router.push({ name: 'visitor.edit', params: { id: item.visitor.id } })"
                >
                    <template #item.user="{ item }">
                        {{ item.user?.name ?? '-' }}
                    </template>
                    <template #item.visitor="{ item }">
                        {{ item.visitor?.name ?? '-' }} {{ item.visitor?.surname ?? '' }}
                    </template>
                    <template #item.created_at="{ item }">
                        {{ new Date(item.created_at).toLocaleString() }}
                    </template>
                    <template #item.meta="{ item }">
                        <pre>{{ formatMeta(item.meta) }}</pre>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>
