<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { echo } from '@/echo'
import emitter from '@/lib/eventBus'
import vuetify from "@/plugins/vuetify";

const snackbar = ref(false)
const message = ref('')
const color = ref('info')

function show(msg: string, type = 'info') {
    message.value = msg
    snackbar.value = true
    color.value = type
}

function handleError(msg: string) {
    show(msg, 'error')
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

    emitter.on('visitor:error', handleError)
})

onUnmounted(() => {
    echo.leave('visitors')
    emitter.off('visitor:error', handleError)
})
</script>

<template>
    <v-snackbar v-model="snackbar" :color="color" timeout="4000" vertical variant="tonal" :location="$vuetify.display.mobile ? 'bottom' : 'top right'">
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
