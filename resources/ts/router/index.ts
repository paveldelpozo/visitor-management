import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import SearchPage from '@/pages/SearchPage.vue'
import EditVisitorPage from '@/pages/EditVisitorPage.vue'
import CreateVisitorPage from '@/pages/CreateVisitorPage.vue'

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'search',
        component: SearchPage,
    },
    {
        path: '/edit/:id',
        name: 'edit',
        component: EditVisitorPage,
        props: true,
    },
    {
        path: '/create',
        name: 'create',
        component: CreateVisitorPage,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
