<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useApi } from '@/composables/useApi'
import { useRouter } from 'vue-router'
import UserForm from "@/components/forms/UserForm.vue";
import ConfirmDialog from "@/components/ui/ConfirmDialog.vue";

const users = ref<any[]>([])
const search = ref('')
const dialog = ref(false)
const dialogDelete = ref(false)
const editedUser = ref<any | null>(null)
const userToDelete = ref<any | null>(null)
const page = ref(1)
const totalPages = ref(1)
const router = useRouter()

const headers = [
    { title: 'Nombre', value: 'name' },
    { title: 'Email', value: 'email' },
    { title: 'Rol', value: 'role' },
    { title: 'Acciones', value: 'actions', sortable: false }
]

const fetchUsers = async () => {
    const { data } = await useApi('get', '/api/users', { params: { search: search.value, page: page.value } })
    if (data) {
        users.value = data.data
        totalPages.value = data.last_page
    }
}

watch([search, page], fetchUsers)
onMounted(fetchUsers)

function openEdit(user: any) {
    editedUser.value = { ...user, password: '', password_confirmation: '' }
    dialog.value = true
}

function openCreate() {
    editedUser.value = null
    dialog.value = true
}

async function saveUser(user: any) {
    if (editedUser.value?.id) {
        await useApi('put', `/api/users/${editedUser.value.id}`, user)
    } else {
        await useApi('post', '/api/users', user)
    }
    dialog.value = false
    await fetchUsers()
}

function confirmDelete(user: any) {
    userToDelete.value = user
    dialogDelete.value = true
}

async function deleteUser() {
    if (userToDelete.value) {
        await useApi('delete', `/api/users/${userToDelete.value.id}`)
        dialogDelete.value = false
        await fetchUsers()
    }
}
</script>

<template>
    <v-container fluid>
        <v-row align="center" class="mb-4">
            <v-col>
                <h1 class="text-h5 font-weight-bold">
                    <v-icon class="mr-2" color="info">mdi-account-cog</v-icon>
                    Usuarios
                </h1>
            </v-col>
            <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="search" variant="solo" label="Buscar" clearable hide-details density="compact" />
            </v-col>
            <v-col cols="auto">
                <v-btn color="primary" @click="openCreate">Nuevo usuario</v-btn>
            </v-col>
        </v-row>

        <v-data-table :items="users" :headers="headers" :items-per-page="10" class="elevation-1 rounded">
            <template #item.role="{ item }">
                <v-chip color="info">{{ item.roles[0]?.name }}</v-chip>
            </template>
            <template #item.actions="{ item }">
                <div class="d-flex justify-end">
                    <v-btn icon size="small" class="mr-2" link :to="{ name: 'logs', query: { user: item.name } }"><v-icon>mdi-file-document-outline</v-icon></v-btn>
                    <v-btn icon size="small" class="mr-2" @click="openEdit(item)"><v-icon>mdi-pencil</v-icon></v-btn>
                    <v-btn icon size="small" color="red" @click="confirmDelete(item)"><v-icon>mdi-delete</v-icon></v-btn>
                </div>
            </template>
        </v-data-table>

        <v-pagination v-model="page" :length="totalPages" class="my-4" />

        <UserForm :open="dialog" :user="editedUser" @submit="saveUser" @close="dialog = false" />

        <ConfirmDialog
            :open="dialogDelete"
            :message="`Esta acción eliminará al usuario ${userToDelete?.name}`"
            @confirm="deleteUser"
            @close="dialogDelete = false"
        />
    </v-container>
</template>
