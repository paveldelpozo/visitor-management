import axios from 'axios'

export default {
    install(app: any) {
        app.config.errorHandler = (err: any, instance: any, info: any) => {
            console.error('Error capturado globalmente:', err)

            axios.post('/api/log-client-error', {
                message: err?.message || 'Error desconocido',
                stack: err?.stack || '',
                info,
                url: window.location.href,
            }).catch(() => {
                console.warn('No se pudo enviar el error al servidor.')
            })
        }
    }
}
