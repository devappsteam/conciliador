import { api } from '@/services/api'
import type { Company, CompanyFilters, CreateCompanyPayload, UpdateCompanyPayload } from '../types'
import type { PaginatedResource } from '@/types/api'

export const companyService = {
	async getAll(filters: CompanyFilters = {}): Promise<PaginatedResource<Company>> {
		const response = await api.get<PaginatedResource<Company>>('/api/v1/companies', {
			params: filters,
		})
		return response.data
	},

	async getByUuid(uuid: string): Promise<{ data: Company }> {
		const response = await api.get<{ data: Company }>(`/api/v1/companies/${uuid}`)
		return response.data
	},

	async create(payload: CreateCompanyPayload): Promise<{ data: Company }> {
		const response = await api.post<{ data: Company }>('/api/v1/companies', payload)
		return response.data
	},

	async update(uuid: string, payload: UpdateCompanyPayload): Promise<{ data: Company }> {
		const response = await api.put<{ data: Company }>(`/api/v1/companies/${uuid}`, payload)
		return response.data
	},

	async delete(uuid: string): Promise<void> {
		await api.delete(`/api/v1/companies/${uuid}`)
	},
}
