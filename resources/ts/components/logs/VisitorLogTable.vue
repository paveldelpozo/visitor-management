<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { VisitorLog } from '@/types/visitor'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const props = defineProps<{ visitorId: number, page?: number, search?: string }>()

const logs = ref<VisitorLog[]>([])

const headers = [
    { title: 'Usuario', value: 'user' },
    { title: 'Acción', value: 'action' },
    { title: 'Fecha', value: 'created_at' }
]

async function loadLogs() {
    const { data, error, status } = await useApi('get', `/api/visitors/${props.visitorId}/logs?search=${props.search}&page=${props.page}`)

    if (error) {
        catchError('Ocurrió un error al intentar obtener los registros del asistente.', error)
    } else {
        logs.value = data.data
    }
}

onMounted(loadLogs)
</script>

<template>
    <div class="px-1 py-1 mt-0" style="margin-top: 0 !important;">
        <v-data-table :headers="headers" :items="logs" class="elevation-1">
            <template #item.user="{ item }">
                {{ item.user?.name ?? '-' }}
            </template>
            <template #item.created_at="{ item }">
                {{ new Date(item.created_at).toLocaleString() }}
            </template>
        </v-data-table>
    </div>
</template>
