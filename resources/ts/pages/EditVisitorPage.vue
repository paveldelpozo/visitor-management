<script setup lang="ts">
import {onMounted, ref, watch} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import type { Visitor } from '@/types/visitor'
import VisitorForm from '@/components/forms/VisitorForm.vue'
import VisitorLogTable from '@/components/logs/VisitorLogTable.vue'

const route = useRoute()
const router = useRouter()
const visitor = ref<Visitor | null>(null)

const tab = ref(route.query.tab ?? 'details')

async function load() {
    const { data } = await axios.get(`/api/visitors/${route.params.id}`)
    visitor.value = data
}

function reload() {
    load()
}

onMounted(load)

watch(tab, () => {
    router.push({ query: { tab: tab.value } })
})
</script>

<template>
    <v-container v-if="visitor">
        <VCard>
            <v-tabs v-model="tab" color="primary">
                <v-tab value="details">
                    <v-icon class="mr-2">mdi-account</v-icon>
                    Visitante
                </v-tab>
                <VTab value="logs">
                    <v-icon class="mr-2">mdi-history</v-icon>
                    Logs
                </VTab>
            </v-tabs>

            <v-divider />

            <VCardText>
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="details">
                        <VisitorForm :visitor="visitor" isEdit @updated="reload" />
                    </v-tabs-window-item>
                    <v-tabs-window-item value="logs">
                        <VisitorLogTable :visitorId="visitor.id" class="mt-6" />
                    </v-tabs-window-item>
                </v-tabs-window>
            </VCardText>
        </VCard>
    </v-container>
</template>
