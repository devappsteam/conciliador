import { defineStore } from 'pinia'
import { ref } from 'vue'
import { bankService } from '../services/bankService'
import type { Bank, BankFilters } from '../types'
import type { PaginatedResource } from '@/types/api'
import { normalizeApiError } from '../../utils/normalizeApiError'

export const useBankStore = defineStore('bankManagement', () => {
	const banks = ref<Bank[]>([])
	const meta = ref<PaginatedResource<Bank>['meta'] | null>(null)
	const loading = ref(false)
	const deleting = ref(false)
	const togglingUuid = ref<string | null>(null)
	const error = ref<string | null>(null)
	const deleteError = ref<string | null>(null)

	const currentFilters = ref<BankFilters>({
		page: 1,
		per_page: 15,
		search: '',
		status: '',
	})

	const fetchBanks = async (filters: BankFilters = {}) => {
		loading.value = true
		error.value = null

		currentFilters.value = { ...currentFilters.value, ...filters }

		try {
			const response = await bankService.getAll(currentFilters.value)
			banks.value = response.data
			meta.value = response.meta
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao carregar lista de bancos.'
		} finally {
			loading.value = false
		}
	}

	const changePage = async (page: number) => {
		if (meta.value && page >= 1 && page <= meta.value.last_page) {
			await fetchBanks({ page })
		}
	}

	const deleteBank = async (uuid: string) => {
		deleting.value = true
		deleteError.value = null

		const currentPage = currentFilters.value.page ?? 1
		const isLastItemOnPage = banks.value.length === 1 && currentPage > 1
		const nextPage = isLastItemOnPage ? currentPage - 1 : currentPage

		try {
			await bankService.delete(uuid)
			await fetchBanks({ page: nextPage })
		} catch (err: unknown) {
			deleteError.value = normalizeApiError(err).message || 'Erro ao remover banco.'
			throw err
		} finally {
			deleting.value = false
		}
	}

	const toggleStatus = async (uuid: string) => {
		togglingUuid.value = uuid
		error.value = null

		try {
			const response = await bankService.toggleStatus(uuid)
			const index = banks.value.findIndex((item) => item.uuid === uuid)

			if (index !== -1) {
				banks.value[index] = response.data
			}
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao alterar status do banco.'
		} finally {
			togglingUuid.value = null
		}
	}

	const clearDeleteError = () => {
		deleteError.value = null
	}

	return {
		banks,
		meta,
		loading,
		deleting,
		togglingUuid,
		error,
		deleteError,
		currentFilters,
		fetchBanks,
		changePage,
		deleteBank,
		toggleStatus,
		clearDeleteError,
	}
})
