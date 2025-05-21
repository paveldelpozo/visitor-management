<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import type { Visitor } from '@/types/visitor'
import VisitorForm from '@/components/forms/VisitorForm.vue'
import VisitorLogTable from '@/components/logs/VisitorLogTable.vue'

const route = useRoute()
const visitor = ref<Visitor | null>(null)

async function load() {
    const { data } = await axios.get(`/api/visitors/${route.params.id}`)
    visitor.value = data
}

function reload() {
    load()
}

onMounted(load)
</script>

<template>
    <v-container v-if="visitor">
        <VisitorForm :visitor="visitor" isEdit @updated="reload" />
        <VisitorLogTable :visitorId="visitor.id" class="mt-6" />
    </v-container>
</template>
