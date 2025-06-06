<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import type { Visitor } from "@/types/visitor";
import { echo } from "@/echo";
import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";
import HeaderTitle from "@/components/ui/HeaderTitle.vue";

const visitors = ref<Visitor[]>([])
const total = ref(0)
const loading = ref(true)
const page = ref(1)
const perPage = ref(10)
const search = ref('')
const router = useRouter()

const headers = [
    { title: 'Nombre', value: 'name', nowrap: true },
    { title: 'Apellidos', value: 'surname', nowrap: true },
    { title: 'Teléfono', value: 'phone', nowrap: true },
    { title: 'Email', value: 'email', nowrap: true },
    { title: 'Empresa', value: 'company', nowrap: true },
    { title: 'Auriculares', value: 'headphones', nowrap: true },
    { title: 'Fecha de creación', value: 'created_at', nowrap: true, sortable: false },
    { title: 'Última actualización', value: 'updated_at', nowrap: true, sortable: false },
    { title: 'Acciones', value: 'actions', nowrap: true, sortable: false },
]

async function fetchVisitors({ page: tablePage, itemsPerPage, sortBy }) {
    console.log('Fetching visitors with params:', { page, itemsPerPage, sortBy })
    loading.value = true

    const params: any = {
        search: search.value ?? '',
        page: tablePage, //page.value,
        size: itemsPerPage, //perPage.value,
    }

    const query = new URLSearchParams(params).toString()

    const { data, error, status } = await useApi('get', `/api/visitors?${query}`)

    if (error) {
        catchError('Ocurrió un error al intentar obtener los asistentes.', error)
    } else {
        visitors.value = data.data
        total.value = data.total
    }
    console.log('Fetching visitors with params:', visitors.value, total.value)
    loading.value = false
}

function editVisitor(visitor: any) {
    router.push({ name: 'visitor.edit', params: { id: visitor.id } })
}

const deleting = ref(false)
const errorDeleting = ref<string | null>(null)

const updateVisitor = async (visitor: Visitor, delta: number) => {
    visitor.headphones += delta

    const { data, error, status } = await useApi('put', `/api/visitors/${visitor.id}`, visitor)

    if (error) {
        catchError('Ocurrió un error al intentar actualizar el asistente.', error)
        visitor.headphones -= delta
    }
}

const removeVisitor = async (visitor: Visitor, isActive: any) => {
    deleting.value = true
    errorDeleting.value = null

    const { data, error, status } = await useApi('delete', `/api/visitors/${visitor.id}`)

    if (error || status !== 200) {
        errorDeleting.value = 'Ocurrió un error al intentar eliminar el asistente. Por favor, inténtalo de nuevo más tarde.'
        catchError('Ocurrió un error al intentar eliminar el asistente.', error)
    } else {
        isActive.value = false
    }

    deleting.value = false
}

// watch(search, fetchVisitors)
// watch(page, fetchVisitors)
// watch(perPage, fetchVisitors)

onMounted(() => {
    //fetchVisitors()
    echo.channel('visitors')
        .listenToAll((e: any) => {
            console.log('Evento recibido:', e)
        })
        .listen('.visitors.created', (e: any) => {
            fetchVisitors()
        })
        .listen('.visitors.updated', (e: any) => {
            fetchVisitors()
        })
        .listen('.visitors.deleted', (e: any) => {
            fetchVisitors()
        })
        .listen('.visitors.headphones.changed', (e: any) => {
            fetchVisitors()
        })
})
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <HeaderTitle text="Asistentes" icon="mdi-account-multiple" />

                <div class="d-flex justify-space-between align-top mb-4">
                    <v-text-field
                        v-model="search"
                        autofocus
                        placeholder="Buscar asistente..."
                        hide-details
                        variant="solo"
                        prepend-inner-icon="mdi-magnify"
                        @input="() => fetchVisitors({ page, itemsPerPage: perPage, sortBy: '' })"
                        @keyup.enter="() => fetchVisitors({ page, itemsPerPage: perPage, sortBy: '' })"
                        @click:clear="() => fetchVisitors({ page, itemsPerPage: perPage, sortBy: '' })"
                        class="mb-4"
                        clearable
                    />

                    <v-btn color="info" class="ml-2" size="x-large" link :to="{ name: 'visitor.create' }">
                        <v-icon>mdi-account-plus</v-icon>
                        <span class="ml-2 d-none">Asistente</span>
                    </v-btn>
                </div>

                <v-data-table-server
                    :headers="headers"
                    :items="visitors"
                    v-model:items-per-page="perPage"
                    v-model:page="page"
                    :loading="loading"
                    :items-length="total"
                    :server-items-length="total"
                    class="elevation-1 rounded"
                    @update:options="fetchVisitors"
                >
                    <!-- @update:page="fetchVisitors" -->
                    <template #item.headphones="{ item }">
                        <div class="text-center">
                            <v-btn icon color="info" size="x-small" class="mr-2" @click.stop="updateVisitor(item, -1)">
                                <v-icon>mdi-minus</v-icon>
                            </v-btn>

                            <strong style="display: inline-block; min-width: 20px;">{{ item.headphones }}</strong>

                            <v-btn icon color="info" size="x-small" class=ml-2 @click.stop="updateVisitor(item, 1)">
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <template #item.created_at="{ item }">
                        <div class="text-center">
                            {{ new Date(item.created_at).toLocaleString() }}
                        </div>
                    </template>
                    <template #item.updated_at="{ item }">
                        <div class="text-center">
                            {{ new Date(item.created_at).toLocaleString() }}
                        </div>
                    </template>
                    <template #item.actions="{ item }">
                        <v-btn color="info" icon size="x-small" class="mr-2" @click="editVisitor(item)">
                            <v-icon>mdi-pencil</v-icon>
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
                                        ¿Estás seguro de que deseas eliminar a este asistente: <strong>{{ item.name }} {{ item.surname }}</strong>?
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
                                            @click="removeVisitor(item, isActive)"
                                        ></v-btn>
                                    </v-card-actions>
                                </v-card>
                            </template>
                        </v-dialog>
                    </template>
                </v-data-table-server>
            </v-col>
        </v-row>
    </v-container>
</template>
