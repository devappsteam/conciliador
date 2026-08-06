import { api } from '@/services/api'
import type { CreateUserPayload, UpdateUserPayload, User, UserFilters } from '../types'
import type { PaginatedResource } from '@/types/api'

const buildUserFormData = (
	payload: CreateUserPayload | UpdateUserPayload,
	withMethodOverride = false,
): FormData => {
  const formData = new FormData()

  formData.append('name', payload.name)
  formData.append('email', payload.email)

  formData.append('role', payload.role)

  if (payload.password) {
    formData.append('password', payload.password)
  }

  if (payload.password_confirmation) {
    formData.append('password_confirmation', payload.password_confirmation)
  }

  if (payload.avatar) {
    formData.append('avatar', payload.avatar)
  }

  if (withMethodOverride) {
    formData.append('_method', 'PUT')
  }

  return formData
}

export const userService = {
  async getAll(filters: UserFilters = {}): Promise<PaginatedResource<User>> {
    const response = await api.get<PaginatedResource<User>>('/api/v1/users', {
      params: filters,
    })
    return response.data
  },

  async getByUuid(uuid: string): Promise<{ data: User }> {
    const response = await api.get<{ data: User }>(`/api/v1/users/${uuid}`)
    return response.data
  },

  async create(payload: CreateUserPayload): Promise<{ data: User }> {
    const response = await api.post<{ data: User }>(
      '/api/v1/users',
      buildUserFormData(payload),
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    )
    return response.data
  },

  async update(uuid: string, payload: UpdateUserPayload): Promise<{ data: User }> {
    const response = await api.post<{ data: User }>(
      `/api/v1/users/${uuid}`,
      buildUserFormData(payload, true),
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    )
    return response.data
  },

  async delete(uuid: string): Promise<void> {
    await api.delete(`/api/v1/users/${uuid}`)
  },
}
