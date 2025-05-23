import { createApp } from 'vue'
import { registerSW } from 'virtual:pwa-register'
import App from './App.vue'
import router from './router'
import pinia from './store'
import errorHandler from './plugins/error-handler'
import vuetify from './plugins/vuetify'
import './bootstrap';

registerSW({
    immediate: true,
    onRegistered(r) {
        console.log('Service Worker registrado')
        if (r && confirm('¿Quieres cargar la nueva versión?')) {
            r.swap();
        }
    },
    onNeedRefresh() {
        console.log('Hay una nueva versión disponible')
    },
    onOfflineReady() {
        console.log('La app está lista para usarse sin conexión')
    },
    onRegistrationError(error) {
        console.error('Error en la registración del Service Worker:', error)
    },
})

createApp(App)
  .use(router)
  .use(pinia)
  .use(vuetify)
  .use(errorHandler)
  .mount('#app')
import './echo'
