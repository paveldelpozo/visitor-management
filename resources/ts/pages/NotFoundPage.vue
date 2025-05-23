<template>
    <v-container fluid class="d-flex justify-center">
        <HeaderTitle text="Página no encontrada" />
        <v-btn color="info" :to="{ name: 'home' }">Volver al inicio</v-btn>
    </v-container>
</template>
<script setup lang="ts">
import HeaderTitle from "@/components/ui/HeaderTitle.vue";
</script>
