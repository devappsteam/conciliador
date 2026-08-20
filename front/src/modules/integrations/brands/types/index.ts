export interface Brand {
	uuid: string
	name: string
	code: string
	logo?: string | null
	status: boolean
	created_at?: string
	updated_at?: string
}

export interface BrandFilters {
	search?: string
	status?: '' | 'active' | 'inactive'
	page?: number
	per_page?: number
}

export interface BrandFormValues {
	name: string
	code: string
	status: boolean
	logo: File | null
}

export interface CreateBrandPayload {
	name: string
	code: string
	status: boolean
	logo?: File | null
}

export type UpdateBrandPayload = CreateBrandPayload
