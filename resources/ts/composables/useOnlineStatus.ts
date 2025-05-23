import { ref, onMounted, onUnmounted } from 'vue'

export function useOnlineStatus() {
    const isOnline = ref(navigator.onLine)

    const updateStatus = () => {
        console.log('Network status changed:', navigator.onLine)
        isOnline.value = navigator.onLine
    }

    onMounted(() => {
        window.addEventListener('online', updateStatus)
        window.addEventListener('offline', updateStatus)
    })

    onUnmounted(() => {
        window.removeEventListener('online', updateStatus)
        window.removeEventListener('offline', updateStatus)
    })

    return { isOnline }
}
