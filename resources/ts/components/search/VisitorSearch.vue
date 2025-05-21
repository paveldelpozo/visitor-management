<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { emit } from 'vue'

const form = ref({
    name: '',
    surname: '',
    phone: ''
})

const emitResults = defineEmits(['results'])

async function search() {
    const { data } = await axios.get('/api/visitors/search', {
        params: form.value
    })
    emitResults('results', data)
}
</script>

<template>
    <v-form @submit.prevent="search">
        <v-text-field v-model="form.name" label="Nombre" />
        <v-text-field v-model="form.surname" label="Apellidos" />
        <v-text-field v-model="form.phone" label="Teléfono" />
        <v-btn type="submit" color="primary">Buscar</v-btn>
    </v-form>
</template>
