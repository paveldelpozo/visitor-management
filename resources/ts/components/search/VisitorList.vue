<script setup lang="ts">
import type { Visitor } from '@/types/visitor'
import axios from '@/axios'
import {ref} from 'vue'

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
            emit('refresh') // Emitir evento para refrescar la lista de asistentes
        }
    } catch (error) {
        errorDeleting.value = 'Error al eliminar el asistente. Por favor, inténtalo de nuevo más tarde.'
        console.error('Error al eliminar el asistente:', error)
    }

    deleting.value = false
}

const updateVisitor = async (visitor: Visitor, delta: number) => {
    visitor.headphones += delta

    try {
        await axios.put(`/api/visitors/${visitor.id}`,  visitor)
    } catch (error) {
        console.error('Error al actualizar el asistente:', error)
        visitor.headphones -= delta
    }
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
                        <v-icon size="36" icon="mdi-account" />
                    </template>
                    <v-list-item-title>{{ v.name }} {{ v.surname }}</v-list-item-title>
                    <v-list-item-subtitle>
                        <a class="text-decoration-none font-weight-bold text-primary" :href="`tel:${v.phone}`" v-if="v.phone">{{ v.phone }}</a>
                        <span v-if="v.phone && v.email"> / </span>
                        <span v-if="v.email">{{ v.email }}</span>
                    </v-list-item-subtitle>
                    <template #append>
                        <v-btn icon color="info" size="x-small" class="mr-2" @click.stop="updateVisitor(v, -1)">
                            <v-icon>mdi-minus</v-icon>
                        </v-btn>

                        <VIcon class="mr-2">mdi-headphones</VIcon>
                        <span>{{ v.headphones }}</span>

                        <v-btn icon color="info" size="x-small" class=mx-2 @click.stop="updateVisitor(v, 1)">
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>

                        <v-dialog max-width="500">
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn icon size="x-small" color="error" v-bind="activatorProps" :loading="deleting">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>

                            <template v-slot:default="{ isActive }">
                                <v-card title="Borrar asistente">
                                    <v-card-text>
                                        ¿Estás seguro de que deseas eliminar a este asistente: <strong>{{ v.name }} {{ v.surname }}</strong>?
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

<style>
.v-list-item__spacer {
    width: 16px!important;
}
</style>
