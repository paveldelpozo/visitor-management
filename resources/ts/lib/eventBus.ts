import mitt from 'mitt'

type Events = {
    'visitor:error': string
}

const emitter = mitt<Events>()
export default emitter
