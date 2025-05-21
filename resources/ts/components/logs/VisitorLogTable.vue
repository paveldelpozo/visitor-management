<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import type { VisitorLog } from '@/types/visitor'

const props = defineProps<{ visitorId: number, page: number, search: string }>()

const logs = ref<VisitorLog[]>([])

const headers = [
    { text: 'Usuario', value: 'user_id' },
    { text: 'Acción', value: 'action' },
    { text: 'Fecha', value: 'created_at' }
]

async function loadLogs() {
    const { data } = await axios.get(`/api/visitors/${props.visitorId}/logs?search=${props.search}&page=${props.page}`)
    logs.value = data
}

onMounted(loadLogs)
</script>

<template>
    <v-data-table :headers="headers" :items="logs" class="elevation-1" />
</template>
