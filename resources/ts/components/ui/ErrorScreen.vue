<template>
  <v-container class="fill-height d-flex flex-column align-center justify-center text-center">
    <v-icon size="64" color="error">mdi-alert-circle</v-icon>
    <h2 class="text-h5 mt-4">{{ title }}</h2>
    <p class="text-subtitle-1 mb-4">{{ message }}</p>
    <v-btn color="info" @click="reload">Reintentar</v-btn>
  </v-container>
</template>

<script setup lang="ts">
defineProps<{
  title?: string
  message?: string
}>()

function reload() {
  window.location.reload()
}
</script>
