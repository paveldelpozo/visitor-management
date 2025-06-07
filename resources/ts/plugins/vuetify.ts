import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { VPullToRefresh } from 'vuetify/labs/VPullToRefresh'


// Importa los estilos base de Vuetify
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

export default createVuetify({
    components: { ...components, VPullToRefresh },
    directives,
    defaults: {
        global: {
            ripple: true,
        },
    },
})
