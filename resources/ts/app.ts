import '../css/app.css';
import './bootstrap.ts';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import router from './router'
import App from './App.vue'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

// @ts-ignore
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: { mdi },
    },
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // @ts-ignore
    setup({ el, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(router)
            .use(vuetify)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
