export interface Visitor {
    id: number
    name: string
    surname: string
    company?: string
    identity_id?: string
    phone: string
    email?: string
    headphones: number
    created_at: string
    updated_at: string
}

export interface VisitorLog {
    id: number
    visitor_id: number
    user_id: number
    action: string
    created_at: string
}

export interface HeadphoneStock {
    total: number
    used: number
    available: number
}
