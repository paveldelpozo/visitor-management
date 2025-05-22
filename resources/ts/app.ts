import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import pinia from './store'
import errorHandler from './plugins/error-handler'
import vuetify from './plugins/vuetify'
import './bootstrap';

createApp(App)
  .use(router)
  .use(pinia)
  .use(vuetify)
  .use(errorHandler)
  .mount('#app')
import './echo'
