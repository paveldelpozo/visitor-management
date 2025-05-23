<script setup lang="ts">
const props = defineProps({
    icon: {
        type: String,
        required: false
    },
    text: {
        type: String,
        required: false
    },
})

const slots = defineSlots()
</script>

<template>
    <h1 class="text-subtitle-1 text-md-h5 mb-4">
        <v-icon v-if="props.icon" class="mr-2" color="info">{{ props.icon }}</v-icon>
        <span v-if="props.text">{{ props.text }}</span>
        <slot v-if="slots.default" />
    </h1>
</template>

<style scoped>

</style>
