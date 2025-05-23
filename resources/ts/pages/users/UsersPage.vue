<script setup lang="ts">
import { storeToRefs } from "pinia";
import { ref, onMounted, watch } from 'vue'
import { useApi } from '@/composables/useApi'
import { useRoute, useRouter } from 'vue-router'
import UserForm from "@/components/forms/UserForm.vue";
import ConfirmDialog from "@/components/ui/ConfirmDialog.vue";
import { useAuthStore } from '@/store/auth'
import HeaderTitle from "@/components/ui/HeaderTitle.vue";

const route = useRoute()
const router = useRouter()

const users = ref<any[]>([])
const search = ref('')
const dialog = ref(false)
const dialogDelete = ref(false)
const editedUser = ref<any | null>(null)
const userToDelete = ref<any | null>(null)
const page = ref(1)
const totalPages = ref(1)

const auth = useAuthStore()
const { user } = storeToRefs(useAuthStore())

const headers = [
    { title: 'Nombre', value: 'name', nowrap: true },
    { title: 'Email', value: 'email', nowrap: true },
    { title: 'Rol', value: 'role', nowrap: true },
    { title: 'Acciones', value: 'actions', nowrap: true, sortable: false }
]

const fetchUsers = async () => {
    const params: any = {
        search: search.value,
        page: page.value
    }

    const query = new URLSearchParams(params).toString()

    const { data } = await useApi('get', `/api/users?${query}`)
    if (data) {
        users.value = data.data
        totalPages.value = data.last_page
    }
}

function openEdit(userData: any) {
    editedUser.value = { ...userData, password: '', password_confirmation: '' }
    dialog.value = true
    router.push({ query: { user: editedUser.value.id } })
}

function openCreate() {
    editedUser.value = null
    dialog.value = true
}

function closeDialog() {
    dialog.value = false
    router.push({ query: {} })
}

async function logout() {
    try {
        await auth.logout()
        setTimeout(() => router.push({ name: 'login' }), 50)
    } catch (e) {
        console.error('Error al cerrar sesión:', e)
    }
}

async function saveUser(userData: any) {
    if (editedUser.value?.id) {
        await useApi('put', `/api/users/${editedUser.value.id}`, userData)
    } else {
        await useApi('post', '/api/users', userData)
    }
    closeDialog()
    await fetchUsers()

    if (user.value?.id === editedUser.value.id && userData.password && userData.password_confirmation) {
        await logout()
    }
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

function checkQueryUser() {
    if (route.query.user) {
        const userId = parseInt(route.query.user as string)
        const userToEdit = users.value.find((user: any) => user.id === userId)
        if (userToEdit) {
            openEdit(userToEdit)
        }
    }
}

watch([search, page], fetchUsers)

watch(() => route.query, () => {
    checkQueryUser()
})

onMounted(async () => {
    await fetchUsers()

    checkQueryUser()
})
</script>

<template>
    <v-container fluid>
        <v-row align="center" class="mb-4">
            <v-col>
                <HeaderTitle text="Usuarios" icon="mdi-account-cog" />
            </v-col>
            <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="search" variant="solo" placeholder="Buscar" clearable hide-details density="compact" />
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

        <UserForm :open="dialog" :user="editedUser" @submit="saveUser" @close="closeDialog" />

        <ConfirmDialog
            :open="dialogDelete"
            :message="`Esta acción eliminará al usuario ${userToDelete?.name}`"
            @confirm="deleteUser"
            @close="closeDialog"
        />
    </v-container>
</template>
