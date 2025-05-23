import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { createApp, getCurrentInstance } from 'vue'
import IndexPage from '@/pages/IndexPage.vue'
import EditVisitorPage from '@/pages/visitors/EditVisitorPage.vue'
import CreateVisitorPage from '@/pages/visitors/CreateVisitorPage.vue'
import LogsPage from '@/pages/logs/LogsPage.vue'
import UsersPage from "@/pages/users/UsersPage.vue";

const routes: RouteRecordRaw[] = [
    { path: '/', name: 'home', component: IndexPage, meta: { requiresAuth: true } },
    { path: '/visitors', name: 'visitor.index', component: () => import('@/pages/visitors/VisitorsPage.vue'), meta: { requiresAuth: true } },
    { path: '/visitors/create', name: 'visitor.create', component: CreateVisitorPage, meta: { requiresAuth: true } },
    { path: '/visitors/:id', name: 'visitor.edit', component: EditVisitorPage, props: true, meta: { requiresAuth: true } },
    { path: '/logs', name: 'logs', component: LogsPage, meta: { requiresAuth: true, requiresAdmin: true } },
    { path: '/login', name: 'login', component: () => import('@/pages/LoginPage.vue') },
    { path: '/users', name: 'users.index', component: UsersPage, meta: { requiresAuth: true, requiresAdmin: true } },
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

    const auth = useAuthStore()

    if (!auth.user && localStorage.getItem('auth_token') && !isLoadingUser) {
        isLoadingUser = true
        await auth.fetchUser()
        isLoadingUser = false
    }

    if (auth.fatal) return next({ name: 'error' })
    if (to.meta.requiresAuth && !auth.user) return next({ name: 'login' })
    if (to.meta.requiresAdmin && auth.user?.roles?.[0]?.name !== 'admin') return next({ name: 'home' })
    if (to.name === 'login' && auth.user) return next({ name: 'home' })

    next()
})

export default router
