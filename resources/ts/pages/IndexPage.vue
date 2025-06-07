<script setup lang="ts">
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'
import { echo } from '@/echo'
import VisitorSearch from '@/components/search/VisitorSearch.vue'
import VisitorList from '@/components/search/VisitorList.vue'
import HeadphoneStockIndicator from '@/components/stock/HeadphoneStockIndicator.vue'
import type { Visitor } from '@/types/visitor'
import HeaderTitle from "@/components/ui/HeaderTitle.vue";

const visitorSearchRef = ref<InstanceType<typeof VisitorSearch>>()
const headphoneStockIndicatorRef = ref<InstanceType<typeof HeadphoneStockIndicator>>()
const loading = ref(false)
const visitors = ref<Visitor[]>([])
const router = useRouter()

function handleResults(result: any) {
    // if (result.status === 'single') {
    //     router.push({ name: 'visitor.edit', params: { id: result.visitor.id } })
    // } else if (result.status === 'multiple') {
    //     visitors.value = result.visitors
    // } else {
    //     router.push({ name: 'visitor.create' })
    // }
    visitors.value = result.visitors ?? [result.visitor] ?? []

    if (!['single', 'multiple'].includes(result.status)) {
        router.push({ name: 'visitor.create' })
    }
}

function goToEdit(visitor: Visitor) {
    router.push({ name: 'visitor.edit', params: { id: visitor.id } })
}

onMounted(() => {
    echo.channel('visitors')
        .listenToAll((e: any) => {
            console.log('Evento recibido:', e)
            visitorSearchRef.value?.search()
            headphoneStockIndicatorRef.value?.loadStock()
        })
})
</script>

<template>
    <v-container fluid class="text-center">
        <div class="d-flex justify-center my-4">
            <v-img src="/img/seonthebeach_logo.png" max-width="25%" min-width="320"></v-img>
        </div>

        <HeaderTitle text="Gestión de Auriculares"/>

        <VisitorSearch
            ref="visitorSearchRef"
            @results="handleResults"
            @loading="(value) => loading = value"
        />

        <VisitorList
            v-if="visitors.length >= 1 && !loading"
            :visitors="visitors"
            @select="goToEdit"
            @refresh="visitorSearchRef?.search()"
        />

        <HeadphoneStockIndicator ref="headphoneStockIndicatorRef" class="my-4" />
    </v-container>
</template>
