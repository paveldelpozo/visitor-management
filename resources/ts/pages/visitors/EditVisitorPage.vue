<script setup lang="ts">
import {onMounted, ref, watch} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { Visitor } from '@/types/visitor'
import VisitorForm from '@/components/forms/VisitorForm.vue'
import VisitorLogTable from '@/components/logs/VisitorLogTable.vue'
import { echo } from '@/echo'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const route = useRoute()
const router = useRouter()
const loading = ref(false)
const visitor = ref<Visitor | null>(null)

const tab = ref(route.query.tab ?? 'details')

async function load() {
    loading.value = true

    const { data, error, status } = await useApi('get', `/api/visitors/${route.params.id}`)

    if (error) {
        catchError('Ocurrió un error al intentar obtener los datos del asistente.', error)
    } else {
        visitor.value = data
    }

    loading.value = false
}

function reload() {
    load()
}

onMounted(() => {
    echo.channel('visitors')
        .listenToAll((e: any) => {
            console.log('Evento recibido:', e)
            load()
        })

    load()
})

watch(tab, () => {
    router.push({ query: { tab: tab.value } })
})
</script>

<template>
    <v-container v-if="visitor" fluid>
        <h1 class="text-h5 mb-4">
            <v-icon class="mr-2" color="info">mdi-account</v-icon>
            Edición del asistente: <strong class="text-info">{{ visitor.name }} {{ visitor.surname }}</strong>
        </h1>

        <VCard :loading="loading">
            <v-tabs v-model="tab" color="info">
                <v-tab value="details">
                    <v-icon class="mr-2">mdi-account</v-icon>
                    Asistente
                </v-tab>
                <VTab value="logs">
                    <v-icon class="mr-2">mdi-history</v-icon>
                    Logs
                </VTab>
            </v-tabs>

            <v-divider />

            <VCardText v-if="!loading">
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="details">
                        <VisitorForm :visitor="visitor" isEdit @updated="reload" />
                    </v-tabs-window-item>
                    <v-tabs-window-item value="logs">
                        <VisitorLogTable :visitorId="visitor.id" class="mt-6" />
                    </v-tabs-window-item>
                </v-tabs-window>
            </VCardText>

            <VCardText v-if="loading" class="d-flex justify-center py-8 py-lg-16">
                <v-progress-circular indeterminate aria-label="Cargando" color="info"></v-progress-circular>
            </VCardText>
        </VCard>
    </v-container>
</template>
