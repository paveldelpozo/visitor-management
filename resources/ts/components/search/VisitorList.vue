<script setup lang="ts">
import type { Visitor } from '@/types/visitor'
import {ref} from 'vue'
import { useRouter } from 'vue-router'
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

defineProps<{ visitors: Visitor[] }>()
const emit = defineEmits(['select', 'refresh'])

const router = useRouter()

const pullDownThreshold = ref(64)

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
    <div class="scrollable-container bg-surface-light text-left border rounded elevation-1">
        <v-pull-to-refresh
            :pull-down-threshold="pullDownThreshold"
            @load="emit('refresh')"
        >
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
                        <v-list-item-title>{{ v.name }} {{ v.surname }} <span v-if="v.company" class="text-grey">({{ v.company }})</span></v-list-item-title>
                        <v-list-item-subtitle>
                            <a v-if="v.phone" :href="`tel:${v.phone}`" class="font-weight-bold text-info">{{ v.phone }}</a>
                            <span v-if="v.phone && v.email"> / </span>
                            <a :href="`mailto:${v.email}`" class="font-weight-bold text-grey">{{ v.email }}</a>
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
        </v-pull-to-refresh>
    </div>
    <v-divider v-if="visitors.length > 0" />
    <div class="pa-2 text-center">
        <v-btn variant="text" block :to="{ name: 'visitor.index' }" class="text-decoration-none text-info">
            Ver todos los asistentes
        </v-btn>
    </div>
</template>

<style>
.scrollable-container {
    max-height: 60dvh;
    overflow-y: scroll;
}
@media (max-width: 600px) {
    .scrollable-container {
        max-height: 40dvh;
    }
}

.v-list-item__spacer {
    width: 8px!important;
}
</style>
