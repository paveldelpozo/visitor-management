<script setup lang="ts">
import type { Visitor } from '@/types/visitor'
import axios from 'axios'
import {ref} from 'vue'

Echo.channel('visitors')
    .listen('.updated', (e: any) => {
        console.log('Actualización en tiempo real:', e.visitor)
        // Puedes emitir un evento, recargar datos, etc.
    })

defineProps<{ visitors: Visitor[] }>()
const emit = defineEmits(['select', 'refresh'])

const deleting = ref(false)
const errorDeleting = ref<string | null>(null)

const removeVisitor = async (visitor: Visitor, isActive: any) => {
    deleting.value = true
    errorDeleting.value = null

    try {
        const response = await axios.delete(`/api/visitors/${visitor.id}`)
        if (response.status === 200) {
            isActive.value = false
            emit('refresh') // Emitir evento para refrescar la lista de visitantes
        }
    } catch (error) {
        errorDeleting.value = 'Error al eliminar el visitante. Por favor, inténtalo de nuevo más tarde.'
        console.error('Error al eliminar el visitante:', error)
    }

    deleting.value = false
}
</script>

<template>
    <h3 class="text-left">Coincidencias:</h3>
    <div class="text-left border rounded elevation-1">
        <v-list>
            <template
                v-for="(v, i) in visitors"
                :key="v.id"
            >
                <v-divider v-if="i > 0" />
                <v-list-item @click="() => emit('select', v)">
                    <template #prepend>
                        <v-avatar size="40" class="mr-2" icon="mdi-account" />
                    </template>
                    <v-list-item-title>{{ v.name }} {{ v.surname }}</v-list-item-title>
                    <v-list-item-subtitle>{{ v.phone }}</v-list-item-subtitle>
                    <template #append>
                        <VIcon class="mr-2">mdi-headphones</VIcon>
                        <span>{{ v.headphones }}</span>

                        <v-dialog max-width="500">
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn icon size="x-small" color="error" class="ml-2" v-bind="activatorProps" :loading="deleting">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>

                            <template v-slot:default="{ isActive }">
                                <v-card title="Borrar visitante">
                                    <v-card-text>
                                        ¿Estás seguro de que deseas eliminar a este visitante: <strong>{{ v.name }} {{ v.surname }}</strong>?
                                    </v-card-text>

                                    <v-card-text v-if="errorDeleting">
                                        <v-alert
                                            type="error"
                                            :message="errorDeleting"
                                            dismissible
                                        />
                                    </v-card-text>

                                    <v-card-actions class="px-4 pb-4">
                                        <v-btn
                                            text="Cancelar"
                                            @click="isActive.value = false"
                                        ></v-btn>

                                        <v-spacer></v-spacer>

                                        <v-btn
                                            variant="flat"
                                            color="error"
                                            text="Si, borrar"
                                            :loading="deleting"
                                            @click="removeVisitor(v, isActive)"
                                        ></v-btn>
                                    </v-card-actions>
                                </v-card>
                            </template>
                        </v-dialog>
                    </template>
                </v-list-item>
            </template>
        </v-list>
    </div>
</template>
