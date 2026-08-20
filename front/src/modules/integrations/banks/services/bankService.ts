import { api } from '@/services/api'
import type { Bank, BankFilters, CreateBankPayload, UpdateBankPayload } from '../types'
import type { PaginatedResource } from '@/types/api'

const buildBankFormData = (
	payload: CreateBankPayload | UpdateBankPayload,
	withMethodOverride = false,
): FormData => {
	const formData = new FormData()

	formData.append('name', payload.name)
	formData.append('code', payload.code)
	formData.append('status', payload.status ? '1' : '0')

	if (payload.logo) {
		formData.append('logo', payload.logo)
	}

	if (withMethodOverride) {
		formData.append('_method', 'PUT')
	}

	return formData
}

export const bankService = {
	async getAll(filters: BankFilters = {}): Promise<PaginatedResource<Bank>> {
		const response = await api.get<PaginatedResource<Bank>>('/api/v1/banks', {
			params: filters,
		})
		return response.data
	},

	async getByUuid(uuid: string): Promise<{ data: Bank }> {
		const response = await api.get<{ data: Bank }>(`/api/v1/banks/${uuid}`)
		return response.data
	},

	async create(payload: CreateBankPayload): Promise<{ data: Bank }> {
		const response = await api.post<{ data: Bank }>(
			'/api/v1/banks',
			buildBankFormData(payload),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async update(uuid: string, payload: UpdateBankPayload): Promise<{ data: Bank }> {
		const response = await api.post<{ data: Bank }>(
			`/api/v1/banks/${uuid}`,
			buildBankFormData(payload, true),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async delete(uuid: string): Promise<void> {
		await api.delete(`/api/v1/banks/${uuid}`)
	},

	async toggleStatus(uuid: string): Promise<{ data: Bank }> {
		const response = await api.patch<{ data: Bank }>(`/api/v1/banks/${uuid}/toggle-status`)
		return response.data
	},
}
