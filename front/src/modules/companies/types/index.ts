export type CompanyStatus = 'pending' | 'active' | 'inactive' | 'suspended'

export interface Company {
	uuid: string
	document: string
	corporate_name: string
	trade_name?: string | null
	state_registration?: string | null
	municipal_registration?: string | null
	email?: string | null
	phone?: string | null
	street: string
	number?: string | null
	complement?: string | null
	neighborhood?: string | null
	city?: string | null
	state?: string | null
	zip_code?: string | null
	country?: string | null
	status: CompanyStatus
	status_label?: string
	created_at?: string
	updated_at?: string
}

export interface CompanyFilters {
	search?: string
	status?: CompanyStatus | ''
	page?: number
	per_page?: number
}

export interface CompanyFormValues {
	document: string
	corporate_name: string
	trade_name: string
	state_registration: string
	municipal_registration: string
	email: string
	phone: string
	street: string
	number: string
	complement: string
	neighborhood: string
	city: string
	state: string
	zip_code: string
	country: string
	status: CompanyStatus
}

export type CreateCompanyPayload = CompanyFormValues
export type UpdateCompanyPayload = Omit<CompanyFormValues, 'document'>
