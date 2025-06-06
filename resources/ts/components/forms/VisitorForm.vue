<script setup lang="ts">
import { ref, watch } from 'vue'
import type { Visitor } from '@/types/visitor'
import { useRouter } from 'vue-router'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

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

async function create() {
    const { data, error, status } = await useApi('post', '/api/visitors', form.value)

    if (error) {
        catchError('Se produjo un error al intentar crear el nuevo asistente', error)
    } else {
        emit('updated')
    }
}

async function update() {
    const { data, error, status } = await useApi('put', `/api/visitors/${props.visitor?.id}`, form.value)

    if (error) {
        catchError('Se produjo un error al intentar actualizar al asistente', error)
    } else {
        emit('updated')
    }
}

async function submit() {
    if (!props.isEdit) {
        await create()
    } else {
        await update()
    }
    await router.push({ name: 'home' })
}
</script>

<template>
    <v-form @submit.prevent="submit" class="px-1 pb-1">
        <v-row>
            <v-col cols="12">
                <v-text-field v-model="form.name" autofocus variant="solo" placeholder="Nombre" hide-details required />
            </v-col>
            <v-col cols="12">
                <v-text-field v-model="form.surname" variant="solo" placeholder="Apellidos" hide-details required />
            </v-col>
            <v-col cols="12">
                <v-text-field v-model="form.company" variant="solo" placeholder="Empresa" hide-details />
            </v-col>
            <v-col cols="12">
                <v-text-field v-model="form.identity_id" variant="solo" placeholder="DNI/Pasaporte" hide-details />
            </v-col>
            <v-col cols="12">
                <v-text-field v-model="form.phone" variant="solo" placeholder="Teléfono" hide-details type="tel" inputmode="tel" required />
            </v-col>
            <v-col cols="12">
                <v-text-field v-model="form.email" variant="solo" placeholder="Email" hide-details type="email" inputmode="email" />
            </v-col>
        </v-row>

        <div class="d-flex justify-center my-4 mb-8">
            <div class="d-flex align-center pa-2 border rounded-lg elevation-2">
                <v-btn color="info" size="x-large" @click="decrease" :disabled="(form?.headphones ?? 0) <= 0" height="60">
                    <v-icon>mdi-minus</v-icon>
                </v-btn>

                <div class="mx-4 text-h2 font-weight-bold text-center" style="width: 72px;">
                    {{ form?.headphones }}
                </div>

                <v-btn color="info" size="x-large" @click="increase" :disabled="(form?.headphones ?? 0) >= 2" height="60">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </div>
        </div>

        <v-btn block type="submit" color="info" class="mt-4" size="large">
            {{ props.isEdit ? 'Actualizar' : 'Crear' }}
        </v-btn>
    </v-form>
</template>
