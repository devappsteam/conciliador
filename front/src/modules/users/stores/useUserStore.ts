import { defineStore } from 'pinia'
import { ref } from 'vue'
import { userService } from '../services/userService'
import type { User, UserFilters } from '../types'
import type { PaginatedResource } from '@/types/api'

export const useUserStore = defineStore('userManagement', () => {
  const users = ref<User[]>([])
  const meta = ref<PaginatedResource<User>['meta'] | null>(null)
  const loading = ref<boolean>(false)
  const deleting = ref<boolean>(false)
  const error = ref<string | null>(null)
  const deleteError = ref<string | null>(null)

  const currentFilters = ref<UserFilters>({
    page: 1,
    per_page: 15,
    search: '',
    role: '',
  })

  const fetchUsers = async (filters: UserFilters = {}) => {
    loading.value = true
    error.value = null

    // Merge de filtros preservando o estado atual de paginação
    currentFilters.value = { ...currentFilters.value, ...filters }

    try {
      const response = await userService.getAll(currentFilters.value)
      users.value = response.data
      meta.value = response.meta
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Erro ao carregar lista de utilizadores.'
    } finally {
      loading.value = false
    }
  }

  const changePage = async (page: number) => {
    if (meta.value && page >= 1 && page <= meta.value.last_page) {
      await fetchUsers({ page })
    }
  }

  const deleteUser = async (uuid: string) => {
    deleting.value = true
    deleteError.value = null

    const currentPage = currentFilters.value.page ?? 1
    const isLastItemOnPage = users.value.length === 1 && currentPage > 1
    const nextPage = isLastItemOnPage ? currentPage - 1 : currentPage

    try {
      await userService.delete(uuid)
      await fetchUsers({ page: nextPage })
    } catch (err: any) {
      deleteError.value = err.response?.data?.message || 'Erro ao remover utilizador.'
      throw err
    } finally {
      deleting.value = false
    }
  }

  const clearDeleteError = () => {
    deleteError.value = null
  }

  return {
    users,
    meta,
    loading,
    deleting,
    error,
    deleteError,
    currentFilters,
    fetchUsers,
    changePage,
    deleteUser,
    clearDeleteError,
  }
})
