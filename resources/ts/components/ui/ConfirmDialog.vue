<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

const props = defineProps<{
    open: boolean,
    title?: string,
    message: string
}>()

const emit = defineEmits(['confirm', 'close'])
</script>

<template>
    <v-dialog :model-value="props.open" max-width="400" @update:modelValue="emit('close')">
        <v-card>
            <v-card-title class="text-h6">
                {{ props.title || '¿Estás seguro?' }}
            </v-card-title>
            <v-card-text>
                {{ props.message }}
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn @click="emit('close')">Cancelar</v-btn>
                <v-btn color="red" @click="emit('confirm')">Confirmar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
