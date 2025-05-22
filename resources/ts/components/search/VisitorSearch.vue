<script setup lang="ts">
import {onMounted, ref} from 'vue'
import axios from 'axios'

const form = ref({
    search: '',
    // name: '',
    // surname: '',
    // phone: ''
})

const emitResults = defineEmits(['results'])

async function search() {
    const { data } = await axios.get('/api/visitors/search', {
        params: form.value
    })
    emitResults('results', data)
}

onMounted(search)

defineExpose({ search })
</script>

<template>
    <v-form @submit.prevent="search">
        <v-text-field v-model="form.search" autofocus variant="solo" clearable label="Buscar por nombre, teléfono, email, etc." @keyup.enter="search">
            <template #append-inner>
                <v-icon @click="search">mdi-magnify</v-icon>
            </template>
        </v-text-field>
<!--        <v-text-field v-model="form.name" label="Nombre" />-->
<!--        <v-text-field v-model="form.surname" label="Apellidos" />-->
<!--        <v-text-field v-model="form.phone" label="Teléfono" />-->
<!--        <v-btn type="submit" color="primary">Buscar</v-btn>-->
    </v-form>
</template>
