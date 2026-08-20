import { defineStore } from 'pinia'
import { ref } from 'vue'
import { acquirerService } from '../services/acquirerService'
import type { Acquirer, AcquirerFilters } from '../types'
import type { PaginatedResource } from '@/types/api'
import { normalizeApiError } from '../../utils/normalizeApiError'

export const useAcquirerStore = defineStore('acquirerManagement', () => {
	const acquirers = ref<Acquirer[]>([])
	const meta = ref<PaginatedResource<Acquirer>['meta'] | null>(null)
	const loading = ref(false)
	const deleting = ref(false)
	const togglingUuid = ref<string | null>(null)
	const error = ref<string | null>(null)
	const deleteError = ref<string | null>(null)

	const currentFilters = ref<AcquirerFilters>({
		page: 1,
		per_page: 15,
		search: '',
		status: '',
	})

	const fetchAcquirers = async (filters: AcquirerFilters = {}) => {
		loading.value = true
		error.value = null

		currentFilters.value = { ...currentFilters.value, ...filters }

		try {
			const response = await acquirerService.getAll(currentFilters.value)
			acquirers.value = response.data
			meta.value = response.meta
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao carregar lista de adquirentes.'
		} finally {
			loading.value = false
		}
	}

	const changePage = async (page: number) => {
		if (meta.value && page >= 1 && page <= meta.value.last_page) {
			await fetchAcquirers({ page })
		}
	}

	const deleteAcquirer = async (uuid: string) => {
		deleting.value = true
		deleteError.value = null

		const currentPage = currentFilters.value.page ?? 1
		const isLastItemOnPage = acquirers.value.length === 1 && currentPage > 1
		const nextPage = isLastItemOnPage ? currentPage - 1 : currentPage

		try {
			await acquirerService.delete(uuid)
			await fetchAcquirers({ page: nextPage })
		} catch (err: unknown) {
			deleteError.value = normalizeApiError(err).message || 'Erro ao remover adquirente.'
			throw err
		} finally {
			deleting.value = false
		}
	}

	const toggleStatus = async (uuid: string) => {
		togglingUuid.value = uuid
		error.value = null

		try {
			const response = await acquirerService.toggleStatus(uuid)
			const index = acquirers.value.findIndex((item) => item.uuid === uuid)

			if (index !== -1) {
				acquirers.value[index] = response.data
			}
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao alterar status do adquirente.'
		} finally {
			togglingUuid.value = null
		}
	}

	const clearDeleteError = () => {
		deleteError.value = null
	}

	return {
		acquirers,
		meta,
		loading,
		deleting,
		togglingUuid,
		error,
		deleteError,
		currentFilters,
		fetchAcquirers,
		changePage,
		deleteAcquirer,
		toggleStatus,
		clearDeleteError,
	}
})
