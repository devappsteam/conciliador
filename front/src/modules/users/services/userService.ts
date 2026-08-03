import { api } from '@/services/api'
import type { User, UserFilters } from '../types'
import type { PaginatedResource } from '@/types/api'

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

  async create(
    payload: Omit<User, 'uuid' | 'created_at' | 'updated_at' | 'permissions'> & {
      password?: string
    },
  ): Promise<{ data: User }> {
    const response = await api.post<{ data: User }>('/api/v1/users', payload)
    return response.data
  },

  async update(
    uuid: string,
    payload: Partial<User> & { password?: string },
  ): Promise<{ data: User }> {
    const response = await api.put<{ data: User }>(`/api/v1/users/${uuid}`, payload)
    return response.data
  },

  async delete(uuid: string): Promise<void> {
    await api.delete(`/api/v1/users/${uuid}`)
  },
}
