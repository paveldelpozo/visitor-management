<script setup lang="ts">
import VisitorForm from '@/components/forms/VisitorForm.vue'
import HeaderTitle from "@/components/ui/HeaderTitle.vue";
</script>

<template>
    <v-container fluid>
        <HeaderTitle text="Crear asistente" icon="mdi-account-plus" />
        
        <VisitorForm :isEdit="false" />
    </v-container>
</template>
