import { defineStore } from 'pinia'
import { ref } from 'vue'
import { brandService } from '../services/brandService'
import type { Brand, BrandFilters } from '../types'
import type { PaginatedResource } from '@/types/api'
import { normalizeApiError } from '../../utils/normalizeApiError'

export const useBrandStore = defineStore('brandManagement', () => {
	const brands = ref<Brand[]>([])
	const meta = ref<PaginatedResource<Brand>['meta'] | null>(null)
	const loading = ref(false)
	const deleting = ref(false)
	const togglingUuid = ref<string | null>(null)
	const error = ref<string | null>(null)
	const deleteError = ref<string | null>(null)

	const currentFilters = ref<BrandFilters>({
		page: 1,
		per_page: 15,
		search: '',
		status: '',
	})

	const fetchBrands = async (filters: BrandFilters = {}) => {
		loading.value = true
		error.value = null

		currentFilters.value = { ...currentFilters.value, ...filters }

		try {
			const response = await brandService.getAll(currentFilters.value)
			brands.value = response.data
			meta.value = response.meta
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao carregar lista de bandeiras.'
		} finally {
			loading.value = false
		}
	}

	const changePage = async (page: number) => {
		if (meta.value && page >= 1 && page <= meta.value.last_page) {
			await fetchBrands({ page })
		}
	}

	const deleteBrand = async (uuid: string) => {
		deleting.value = true
		deleteError.value = null

		const currentPage = currentFilters.value.page ?? 1
		const isLastItemOnPage = brands.value.length === 1 && currentPage > 1
		const nextPage = isLastItemOnPage ? currentPage - 1 : currentPage

		try {
			await brandService.delete(uuid)
			await fetchBrands({ page: nextPage })
		} catch (err: unknown) {
			deleteError.value = normalizeApiError(err).message || 'Erro ao remover bandeira.'
			throw err
		} finally {
			deleting.value = false
		}
	}

	const toggleStatus = async (uuid: string) => {
		togglingUuid.value = uuid
		error.value = null

		try {
			const response = await brandService.toggleStatus(uuid)
			const index = brands.value.findIndex((item) => item.uuid === uuid)

			if (index !== -1) {
				brands.value[index] = response.data
			}
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao alterar status da bandeira.'
		} finally {
			togglingUuid.value = null
		}
	}

	const clearDeleteError = () => {
		deleteError.value = null
	}

	return {
		brands,
		meta,
		loading,
		deleting,
		togglingUuid,
		error,
		deleteError,
		currentFilters,
		fetchBrands,
		changePage,
		deleteBrand,
		toggleStatus,
		clearDeleteError,
	}
})
