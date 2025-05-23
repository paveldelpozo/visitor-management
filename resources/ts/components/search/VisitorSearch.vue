<script setup lang="ts">
import {onMounted, ref, watch} from 'vue'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

const form = ref({
    search: '',
})

const emitResults = defineEmits(['results', 'loading'])

const loading = ref(false)

async function search() {
    loading.value = true

    const query = new URLSearchParams(form.value).toString()

    const { data, error, status } = await useApi('get', `/api/visitors/search?${query}`)

    if (error) {
        catchError('Ocurrió un error al intentar buscar asistentes.', error)
    } else {
        emitResults('results', data)
    }

    loading.value = false
}

watch(loading, () => {
    emitResults('loading', loading.value)
})

onMounted(search)

defineExpose({ search })
</script>

<template>
    <v-form @submit.prevent="search" class="d-flex justify-space-between align-center mb-4">
        <v-text-field
            v-model="form.search"
            autofocus
            variant="solo"
            hide-details
            clearable
            placeholder="Buscar asistente..."
            prepend-inner-icon="mdi-magnify"
            @keyup.enter="search"
            @click:clear="search"
        />

        <v-btn color="info" class="ml-2" size="x-large" link :to="{ name: 'visitor.create' }">
            <v-icon>mdi-account-plus</v-icon>
            <span class="ml-2 d-none">Asistente</span>
        </v-btn>
    </v-form>
</template>
