import { useApi } from "@/composables/useApi";
import { catchError } from "@/lib/catchErrors";

export default {
    install(app: any) {
        app.config.errorHandler = async (err: any, instance: any, info: any) => {
            console.error('Error capturado globalmente:', err)

            const payload = {
                message: err?.message || 'Error desconocido',
                stack: err?.stack || '',
                info,
                url: window.location.href,
            }

            const { data, error, status } = await useApi('post', '/api/log-client-error', payload)

            if (error) {
                catchError('Error al intentar enviar el error al servidor', error)
            }
        }
    }
}
