<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import type { VisitorLog } from '@/types/visitor'

const props = defineProps<{ visitorId: number, page?: number, search?: string }>()

const logs = ref<VisitorLog[]>([])

const headers = [
    { title: 'Usuario', value: 'user' },
    { title: 'Acción', value: 'action' },
    { title: 'Fecha', value: 'created_at' }
]

async function loadLogs() {
    const { data } = await axios.get(`/api/visitors/${props.visitorId}/logs?search=${props.search}&page=${props.page}`)
    logs.value = data.data
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
