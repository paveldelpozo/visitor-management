<script setup lang="ts">
import VisitorForm from '@/components/forms/VisitorForm.vue'
</script>

<template>
    <v-container fluid>
        <h1 class="text-h5 mb-4">
            <v-icon class="mr-2" color="info">mdi-account-plus</v-icon>
            Crear asistente
        </h1>
        <VisitorForm :isEdit="false" />
    </v-container>
</template>
