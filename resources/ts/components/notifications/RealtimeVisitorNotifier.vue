<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { echo } from '@/echo'

const snackbar = ref(false)
const message = ref('')

function show(msg: string) {
    message.value = msg
    snackbar.value = true
}

onMounted(() => {
    echo.channel('visitors')
        .listen('.visitors.created', (e: any) => {
            show(`Nuevo Asistente: ${e.visitor.name} ${e.visitor.surname}`)
        })
        .listen('.visitors.updated', (e: any) => {
            show(`Asistente actualizado: ${e.visitor.name} ${e.visitor.surname}`)
        })
        .listen('.visitors.deleted', (e: any) => {
            show(`Asistente eliminado (ID: ${e.visitorId})`)
        })
        .listen('.visitors.headphones.changed', (e: any) => {
            const { name, surname, headphones } = e.visitor
            const action = e.change > 0 ? 'aumentó' : 'disminuyó'
            show(`${name} ${surname} ${action} auriculares a ${headphones}`)
        })

    // const channel = window.Echo.channel('visitors')
    //
    // channel.listen('.visitor.created', (e: any) => {
    //
    // })
    //
    // channel.listen('.visitor.updated', (e: any) => {
    //
    // })
    //
    // channel.listen('.visitor.deleted', (e: any) => {
    //
    // })
    //
    // channel.listen('.visitor.headphones.changed', (e: any) => {
    //
    // })
})

onUnmounted(() => {
    window.Echo.leave('visitors')
})
</script>

<template>
    <v-snackbar v-model="snackbar" color="info" timeout="4000" vertical location="top right">
        {{ message }}

        <template v-slot:actions>
            <v-btn
                size="small"
                variant="outlined"
                @click="snackbar = false"
            >
                Cerrar
            </v-btn>
        </template>
    </v-snackbar>
</template>
