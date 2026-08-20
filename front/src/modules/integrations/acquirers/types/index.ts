export interface Acquirer {
	uuid: string
	name: string
	slug: string
	code: string
	logo?: string | null
	status: boolean
	created_at?: string
	updated_at?: string
}

export interface AcquirerFilters {
	search?: string
	status?: '' | 'active' | 'inactive'
	page?: number
	per_page?: number
}

export interface AcquirerFormValues {
	name: string
	slug: string
	code: string
	status: boolean
	logo: File | null
}

export interface CreateAcquirerPayload {
	name: string
	slug: string
	code: string
	status: boolean
	logo?: File | null
}

export type UpdateAcquirerPayload = CreateAcquirerPayload
