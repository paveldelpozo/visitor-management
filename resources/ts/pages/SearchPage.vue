<script setup lang="ts">
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'
import VisitorSearch from '@/components/search/VisitorSearch.vue'
import VisitorList from '@/components/search/VisitorList.vue'
import HeadphoneStockIndicator from '@/components/stock/HeadphoneStockIndicator.vue'
import type { Visitor } from '@/types/visitor'
import { echo } from '@/echo'

const visitorSearch = ref<InstanceType<typeof VisitorSearch>>()
const loading = ref(false)
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

onMounted(() => {
    echo.channel('visitors')
        .listenToAll((e: any) => {
            console.log('Evento recibido:', e)
        })
        .listen('.visitors.created', (e: any) => {
            visitorSearch.value?.search()
        })
        .listen('.visitors.updated', (e: any) => {
            visitorSearch.value?.search()
        })
        .listen('.visitors.deleted', (e: any) => {
            visitorSearch.value?.search()
        })
        .listen('.visitors.headphones.changed', (e: any) => {
            visitorSearch.value?.search()
        })
})
</script>

<template>
    <v-container class="text-center">
        <div class="d-flex justify-center">
            <v-img src="/img/seonthebeach_logo.png" max-width="50%" min-width="320"></v-img>
        </div>

        <h1 class="text-h4 font-weight-bold my-6">Gestión de Auriculares</h1>

        <HeadphoneStockIndicator class="my-4" />

        <VisitorSearch ref="visitorSearch" @results="handleResults" @loading="(value) => loading = value" />

        <VisitorList
            v-if="visitors.length > 1 && !loading"
            :visitors="visitors"
            @select="goToEdit"
            @refresh="visitorSearch?.search()"
        />
    </v-container>
</template>
