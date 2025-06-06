<script setup lang="ts">
import type { Visitor } from '@/types/visitor'
import {ref} from 'vue'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

defineProps<{ visitors: Visitor[] }>()
const emit = defineEmits(['select', 'refresh'])

const updateVisitor = async (visitor: Visitor, delta: number) => {
//    console.log(visitor, delta)
    visitor.headphones += delta

    const { data, error, status } = await useApi('put', `/api/visitors/${visitor.id}`, visitor)

    if (error) {
        catchError('Error al actualizar el asistente', error)
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
                <v-list-item class="px-2" @click="() => emit('select', v)">
                    <template #prepend>
                        <v-icon size="36" icon="mdi-account" />
                    </template>
                    <v-list-item-title>{{ v.name }} {{ v.surname }}</v-list-item-title>
                    <v-list-item-subtitle>
                        <a class="text-decoration-none font-weight-bold text-info" :href="`tel:${v.phone}`" v-if="v.phone">{{ v.phone }}</a>
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
                    </template>
                </v-list-item>
            </template>
        </v-list>
    </div>
</template>

<style>
.v-list-item__spacer {
    width: 8px!important;
}
</style>
