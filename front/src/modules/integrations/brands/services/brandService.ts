import { api } from '@/services/api'
import type { Brand, BrandFilters, CreateBrandPayload, UpdateBrandPayload } from '../types'
import type { PaginatedResource } from '@/types/api'

const buildBrandFormData = (
	payload: CreateBrandPayload | UpdateBrandPayload,
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

export const brandService = {
	async getAll(filters: BrandFilters = {}): Promise<PaginatedResource<Brand>> {
		const response = await api.get<PaginatedResource<Brand>>('/api/v1/brands', {
			params: filters,
		})
		return response.data
	},

	async getByUuid(uuid: string): Promise<{ data: Brand }> {
		const response = await api.get<{ data: Brand }>(`/api/v1/brands/${uuid}`)
		return response.data
	},

	async create(payload: CreateBrandPayload): Promise<{ data: Brand }> {
		const response = await api.post<{ data: Brand }>(
			'/api/v1/brands',
			buildBrandFormData(payload),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async update(uuid: string, payload: UpdateBrandPayload): Promise<{ data: Brand }> {
		const response = await api.post<{ data: Brand }>(
			`/api/v1/brands/${uuid}`,
			buildBrandFormData(payload, true),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async delete(uuid: string): Promise<void> {
		await api.delete(`/api/v1/brands/${uuid}`)
	},

	async toggleStatus(uuid: string): Promise<{ data: Brand }> {
		const response = await api.patch<{ data: Brand }>(`/api/v1/brands/${uuid}/toggle-status`)
		return response.data
	},
}
