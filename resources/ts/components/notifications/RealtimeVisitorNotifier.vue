<template>
  <v-snackbar v-model="snackbar" color="info" timeout="4000">
    {{ message }}
  </v-snackbar>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const snackbar = ref(false)
const message = ref('')

function show(msg: string) {
  message.value = msg
  snackbar.value = true
}

onMounted(() => {
  const channel = window.Echo.channel('visitors')

  channel.listen('.visitor.created', (e: any) => {
    show(`Nuevo visitante: ${e.visitor.name} ${e.visitor.surname}`)
  })

  channel.listen('.visitor.updated', (e: any) => {
    show(`Visitante actualizado: ${e.visitor.name} ${e.visitor.surname}`)
  })

  channel.listen('.visitor.deleted', (e: any) => {
    show(`Visitante eliminado (ID: ${e.visitorId})`)
  })

  channel.listen('.visitor.headphones.changed', (e: any) => {
    const { name, surname, headphones } = e.visitor
    const action = e.change > 0 ? 'aumentó' : 'disminuyó'
    show(`${name} ${surname} ${action} auriculares a ${headphones}`)
  })
})

onUnmounted(() => {
  window.Echo.leave('visitors')
})
</script>