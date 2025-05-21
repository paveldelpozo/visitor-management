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
        router.push('/')
    }
}
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-text-field v-model="form.name" label="Nombre" required />
        <v-text-field v-model="form.surname" label="Apellidos" required />
        <v-text-field v-model="form.company" label="Empresa" />
        <v-text-field v-model="form.identity_id" label="DNI/Pasaporte" />
        <v-text-field v-model="form.phone" label="Teléfono" required />
        <v-text-field v-model="form.email" label="Email" />

        <div class="d-flex align-center mt-4">
            <v-btn @click="decrease" :disabled="form?.headphones <= 0" icon>
                <v-icon>mdi-minus</v-icon>
            </v-btn>

            <div class="mx-4 text-h4 font-weight-bold">
                {{ form.headphones }}
            </div>

            <v-btn @click="increase" :disabled="form?.headphones >= 2" icon>
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </div>

        <v-btn type="submit" color="primary" class="mt-4">
            Guardar
        </v-btn>
    </v-form>
</template>
