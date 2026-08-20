import { api } from '@/services/api'
import type { Role } from '../types'
import type { PaginatedResource } from '@/types/api'

export const roleService = {
  async getAll(): Promise<Role[]> {
    const response = await api.get<PaginatedResource<Role>>('/api/v1/roles', {
      params: { per_page: 100 },
    })
    return response.data.data
  },
}
