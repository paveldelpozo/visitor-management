<script setup lang="ts">
import { ref, watch } from 'vue'
import type { Visitor } from '@/types/visitor'
import axios from 'axios'
import { useRouter } from 'vue-router'

const props = defineProps<{
    visitor?: Visitor
    isEdit: boolean
}>()

const emit = defineEmits(['updated'])

const router = useRouter()

const form = ref<Partial<Visitor>>({
    name: '',
    surname: '',
    company: '',
    identity_id: '',
    phone: '',
    email: '',
    headphones: 0
})

if (props.visitor) {
    form.value = { ...props.visitor }
}

function increase() {
    if (form.value.headphones! < 2) form.value.headphones!++
}

function decrease() {
    if (form.value.headphones! > 0) form.value.headphones!--
}

watch(form, () => {
    if (props.isEdit) update()
})

async function update() {
    await axios.put(`/api/visitors/${props.visitor?.id}`, form.value)
    emit('updated')
}

async function submit() {
    if (!props.isEdit) {
        await axios.post('/api/visitors', form.value)
    } else {
        await axios.put(`/api/visitors/${props.visitor?.id}`, form.value)
    }
    await router.push({ name: 'home' })
}
</script>

<template>
    <v-form @submit.prevent="submit" class="px-1 pb-1">
        <v-text-field v-model="form.name" autofocus variant="solo" label="Nombre" required />
        <v-text-field v-model="form.surname" variant="solo" label="Apellidos" required />
        <v-text-field v-model="form.company" variant="solo" label="Empresa" />
        <v-text-field v-model="form.identity_id" variant="solo" label="DNI/Pasaporte" />
        <v-text-field v-model="form.phone" variant="solo" label="Teléfono" required />
        <v-text-field v-model="form.email" variant="solo" label="Email" />

        <div class="d-flex justify-center my-4 mb-8">
            <div class="d-flex align-center pa-2 border rounded-lg elevation-2">
                <v-btn color="info" size="x-large" @click="decrease" :disabled="form?.headphones <= 0" height="60">
                    <v-icon>mdi-minus</v-icon>
                </v-btn>

                <div class="mx-4 text-h2 font-weight-bold text-center" style="width: 72px;">
                    {{ form?.headphones }}
                </div>

                <v-btn color="info" size="x-large" @click="increase" :disabled="form?.headphones >= 2" height="60">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </div>
        </div>

        <v-btn block type="submit" color="primary" class="mt-4" size="large">
            {{ props.isEdit ? 'Actualizar' : 'Crear' }}
        </v-btn>
    </v-form>
</template>
