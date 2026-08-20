export interface Bank {
	uuid: string
	name: string
	code: string
	logo?: string | null
	status: boolean
	created_at?: string
	updated_at?: string
}

export interface BankFilters {
	search?: string
	status?: '' | 'active' | 'inactive'
	page?: number
	per_page?: number
}

export interface BankFormValues {
	name: string
	code: string
	status: boolean
	logo: File | null
}

export interface CreateBankPayload {
	name: string
	code: string
	status: boolean
	logo?: File | null
}

export type UpdateBankPayload = CreateBankPayload
