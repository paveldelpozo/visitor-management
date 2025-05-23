import axios from '@/axios'

interface ApiResponse<T> {
    data: T | null
    error: string | null
    status: number
}

export async function useApi<T = any>(
    method: 'get' | 'post' | 'put' | 'patch' | 'delete',
    url: string,
    payload?: any
): Promise<ApiResponse<T>> {
    try {
        const response = await axios({
            method,
            url,
            data: payload,
        })

        return {
            data: response.data,
            error: null,
            status: response.status,
        }
    } catch (error: any) {
        const message =
            error.response?.data?.message ||
            error.message ||
            'Unknown error'

        return {
            data: null,
            error: message,
            status: error.response?.status || 500,
        }
    }
}
