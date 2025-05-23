import { useTheme } from 'vuetify'
import { ref, watch } from 'vue'

const storageKey = 'theme:dark'

export function usePersistentTheme() {
    const theme = useTheme()
    const isDark = ref<boolean>(localStorage.getItem(storageKey) === 'true')

    theme.global.name.value = isDark.value ? 'dark' : 'light'

    watch(isDark, (newValue) => {
        theme.global.name.value = newValue ? 'dark' : 'light'
        localStorage.setItem(storageKey, newValue.toString())
    })

    const toggleTheme = () => {
        isDark.value = !isDark.value
    }

    return {
        isDark,
        toggleTheme,
    }
}
