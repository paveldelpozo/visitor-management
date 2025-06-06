<script setup lang="ts">
import {ref, onMounted, watch} from 'vue'
import { useRoute, useRouter } from "vue-router";
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";
import HeaderTitle from "@/components/ui/HeaderTitle.vue";

const route = useRoute()
const router = useRouter()

const logs = ref([])
const total = ref(0)
const loading = ref(true)
const page = ref(1)
const perPage = ref(10)

const filters = ref({
    user: route.query.user || '',
    date: route.query.date || '',
    page: page.value,
    size: perPage.value,
})

const headers = [
    { title: 'Usuario', value: 'user', nowrap: true },
    { title: 'Asistente', value: 'visitor', nowrap: true },
    { title: 'Acción', value: 'action', nowrap: true },
    { title: 'Fecha', value: 'created_at', nowrap: true },
    { title: 'Meta', value: 'meta', nowrap: true },
]

const getParams = ({ tablePage, itemsPerPage, sortBy }) => {
    const params: any = {}

    params.page = tablePage || page.value
    params.size = itemsPerPage || perPage.value

    if (filters.value.user)
        params.user = filters.value.user

    if (filters.value.date)
        params.date = filters.value.date

    return params
}

async function fetchLogs({ page: tablePage, itemsPerPage, sortBy }) {
    loading.value = true

    const queryString = new URLSearchParams(getParams({ tablePage, itemsPerPage, sortBy })).toString()

    const { data, error, status } = await useApi('get', `/api/logs?${queryString}`)

    if (error) {
        catchError('Ocurrió un error al intentar obtener los registros.', error)
    } else {
        logs.value = data.data
        total.value = data.total
    }

    loading.value = false
}

function formatMeta(meta: any) {
    return JSON.stringify(meta, null, 2)
}

// watch(filters, () => {
//     router.push({ query: getParams({ tablePage: page.value, itemsPerPage: perPage.value, sortBy: [] }) })
// }, { deep: true })

//onMounted(fetchLogs)
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <HeaderTitle text="Registro de acciones" icon="mdi-file-document-outline" />

                <v-row class="mb-4" dense>
                    <v-col cols="12" md="5">
                        <v-text-field
                            v-model="filters.user"
                            variant="solo"
                            placeholder="Filtrar por usuario"
                            hide-details
                            clearable
                            prepend-inner-icon="mdi-account"
                            @input="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                            @keyup.enter="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                            @click:clear="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                        />
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-text-field
                            v-model="filters.date"
                            variant="solo"
                            placeholder="Filtrar por fecha (YYYY-MM-DD)"
                            hide-details
                            clearable
                            prepend-inner-icon="mdi-calendar"
                            @input="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                            @keyup.enter="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                            @click:clear="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-btn
                            @click="() => fetchLogs({ page, itemsPerPage: perPage, sortBy: '' })"
                            block
                            color="info"
                            class="mt-2"
                            size="large"
                        >
                            <v-icon>mdi-filter</v-icon>
                            Aplicar filtros
                        </v-btn>
                    </v-col>
                </v-row>

                <v-data-table-server
                    :headers="headers"
                    :items="logs"
                    v-model:items-per-page="perPage"
                    v-model:page="page"
                    :loading="loading"
                    :items-length="total"
                    :server-items-length="total"
                    class="elevation-1"
                    @update:options="fetchLogs"
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
                </v-data-table-server>
            </v-col>
        </v-row>
    </v-container>
</template>
