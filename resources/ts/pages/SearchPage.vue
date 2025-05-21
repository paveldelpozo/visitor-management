<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import VisitorSearch from '@/components/search/VisitorSearch.vue'
import VisitorList from '@/components/search/VisitorList.vue'
import HeadphoneStockIndicator from '@/components/stock/HeadphoneStockIndicator.vue'
import type { Visitor } from '@/types/visitor'

const visitors = ref<Visitor[]>([])
const router = useRouter()

function handleResults(result: any) {
    if (result.status === 'single') {
        router.push(`/edit/${result.visitor.id}`)
    } else if (result.status === 'multiple') {
        visitors.value = result.visitors
    } else {
        router.push('/create')
    }
}

function goToEdit(visitor: Visitor) {
    router.push(`/edit/${visitor.id}`)
}
</script>

<template>
    <v-container>
        <VisitorSearch @results="handleResults" />
        <HeadphoneStockIndicator class="my-4" />
        <VisitorList
            v-if="visitors.length > 1"
            :visitors="visitors"
            @select="goToEdit"
        />
    </v-container>
</template>
