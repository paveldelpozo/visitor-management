import { computed } from 'vue'
import { useAuthStore } from '@/store/auth'

export function usePermissions() {
    const auth = useAuthStore()

    const isAdmin = computed(() => {
        return auth.user?.roles?.[0]?.name === 'admin'
    })

    return { isAdmin }
}
