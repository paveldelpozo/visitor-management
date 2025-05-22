import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { createPinia } from 'pinia'
import { createApp, getCurrentInstance } from 'vue'
import SearchPage from '@/pages/SearchPage.vue'
import EditVisitorPage from '@/pages/EditVisitorPage.vue'
import CreateVisitorPage from '@/pages/CreateVisitorPage.vue'
import LogsPage from '@/pages/LogsPage.vue'

const routes: RouteRecordRaw[] = [
    { path: '/', name: 'search', component: SearchPage, meta: { requiresAuth: true } },
    { path: '/edit/:id', name: 'edit', component: EditVisitorPage, props: true, meta: { requiresAuth: true } },
    { path: '/create', name: 'create', component: CreateVisitorPage, meta: { requiresAuth: true } },
    { path: '/logs', name: 'logs', component: LogsPage, meta: { requiresAuth: true } },
    { path: '/login', name: 'login', component: () => import('@/pages/LoginPage.vue') },
    { path: '/error', name: 'error', component: () => import('@/components/ui/ErrorScreen.vue') },
    { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/pages/NotFoundPage.vue') }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

let isLoadingUser = false

router.beforeEach(async (to, from, next) => {
    const app = getCurrentInstance()?.appContext.app || createApp({})

    // if (!app._context?.plugins.some(p => p === createPinia)) {
    //     app.use(createPinia())
    // }

    const auth = useAuthStore()

    if (!auth.user && localStorage.getItem('auth_token') && !isLoadingUser) {
        isLoadingUser = true
        await auth.fetchUser()
        isLoadingUser = false
    }

    if (auth.fatal) return next({ name: 'error' })
    if (to.meta.requiresAuth && !auth.user) return next({ name: 'login' })
    if (to.meta.requiresAdmin && auth.user?.role !== 'admin') return next({ name: 'search' })
    if (to.name === 'login' && auth.user) return next({ name: 'search' })

    next()
})

export default router
