import { api } from '@/services/api'
import type {
	Acquirer,
	AcquirerFilters,
	CreateAcquirerPayload,
	UpdateAcquirerPayload,
} from '../types'
import type { PaginatedResource } from '@/types/api'

const buildAcquirerFormData = (
	payload: CreateAcquirerPayload | UpdateAcquirerPayload,
	withMethodOverride = false,
): FormData => {
	const formData = new FormData()

	formData.append('name', payload.name)
	formData.append('slug', payload.slug)
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

export const acquirerService = {
	async getAll(filters: AcquirerFilters = {}): Promise<PaginatedResource<Acquirer>> {
		const response = await api.get<PaginatedResource<Acquirer>>('/api/v1/acquirers', {
			params: filters,
		})
		return response.data
	},

	async getByUuid(uuid: string): Promise<{ data: Acquirer }> {
		const response = await api.get<{ data: Acquirer }>(`/api/v1/acquirers/${uuid}`)
		return response.data
	},

	async create(payload: CreateAcquirerPayload): Promise<{ data: Acquirer }> {
		const response = await api.post<{ data: Acquirer }>(
			'/api/v1/acquirers',
			buildAcquirerFormData(payload),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async update(uuid: string, payload: UpdateAcquirerPayload): Promise<{ data: Acquirer }> {
		const response = await api.post<{ data: Acquirer }>(
			`/api/v1/acquirers/${uuid}`,
			buildAcquirerFormData(payload, true),
			{ headers: { 'Content-Type': 'multipart/form-data' } },
		)
		return response.data
	},

	async delete(uuid: string): Promise<void> {
		await api.delete(`/api/v1/acquirers/${uuid}`)
	},

	async toggleStatus(uuid: string): Promise<{ data: Acquirer }> {
		const response = await api.patch<{ data: Acquirer }>(`/api/v1/acquirers/${uuid}/toggle-status`)
		return response.data
	},
}
