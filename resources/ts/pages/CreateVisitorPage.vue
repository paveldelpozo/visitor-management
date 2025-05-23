<script setup lang="ts">
import VisitorForm from '@/components/forms/VisitorForm.vue'
</script>

<template>
    <v-container>
        <h2 class="text-h4 font-weight-bold my-6">Crear asistente</h2>
        <VisitorForm :isEdit="false" />
    </v-container>
</template>
