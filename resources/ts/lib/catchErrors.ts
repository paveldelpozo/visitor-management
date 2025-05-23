import emitter from "@/lib/eventBus";

export function catchError(msg: string, error: any) {
    emitter.emit('visitor:error', msg)
    console.error('Falló la petición:', error)
}
