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

    const { data, error, status } = await useApi('get', '/api/visitors/search', form.value)

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
    <v-form @submit.prevent="search">
        <v-text-field
            v-model="form.search"
            autofocus
            variant="solo"
            clearable
            label="Buscar asistente..."
            prepend-inner-icon="mdi-magnify"
            @input="search"
            @keyup.enter="search"
        />
    </v-form>
</template>
