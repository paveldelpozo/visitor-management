<script setup lang="ts">
import { ref, watch, defineEmits, defineProps } from 'vue'

const emit = defineEmits(['submit', 'close'])

const props = defineProps<{
    user: any | null,
    open: boolean
}>()

const form = ref({
    name: '',
    email: '',
    role: 'user',
    password: '',
    password_confirmation: ''
})

watch(() => props.user, (val) => {
    if (val) {
        form.value = {
            name: val.name,
            email: val.email,
            role: val.roles[0]?.name || 'user',
            password: '',
            password_confirmation: ''
        }
    } else {
        form.value = { name: '', email: '', role: 'user', password: '', password_confirmation: '' }
    }
}, { immediate: true })

function handleSubmit() {
    emit('submit', { ...form.value })
}
</script>

<template>
    <v-dialog :model-value="props.open" max-width="500" @update:modelValue="emit('close')">
        <v-form @submit.prevent="handleSubmit">
            <v-card>
                <v-card-title class="text-h6 bg-info">{{ props.user ? 'Editar usuario' : 'Nuevo usuario' }}</v-card-title>
                <v-card-text>
                    <v-text-field autofocus variant="solo" v-model="form.name" label="Nombre" required />
                    <v-text-field variant="solo" v-model="form.email" label="Email" type="email" required />
                    <v-select
                        variant="solo"
                        v-model="form.role"
                        :items="['admin', 'user']"
                        label="Rol"
                        required
                    />
                    <v-text-field variant="solo" v-model="form.password" autocomplete="password" label="Contraseña" type="password" :required="!props.user" />
                    <v-text-field variant="solo" v-model="form.password_confirmation" autocomplete="password" label="Confirmar contraseña" type="password" :required="!props.user" />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="emit('close')">Cancelar</v-btn>
                    <v-btn color="info" variant="flat" type="submit">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>
