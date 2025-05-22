import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Importa los estilos base de Vuetify
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

export default createVuetify({
    components,
    directives,
    defaults: {
        global: {
            ripple: true,
        },
    },
})
