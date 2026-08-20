import { defineStore } from 'pinia'
import { ref } from 'vue'
import { companyService } from '../services/companyService'
import type { Company, CompanyFilters } from '../types'
import type { PaginatedResource } from '@/types/api'
import { normalizeApiError } from '@/modules/integrations/utils/normalizeApiError'

export const useCompanyStore = defineStore('companyManagement', () => {
	const companies = ref<Company[]>([])
	const meta = ref<PaginatedResource<Company>['meta'] | null>(null)
	const loading = ref(false)
	const deleting = ref(false)
	const error = ref<string | null>(null)
	const deleteError = ref<string | null>(null)

	const currentFilters = ref<CompanyFilters>({
		page: 1,
		per_page: 15,
		search: '',
		status: '',
	})

	const fetchCompanies = async (filters: CompanyFilters = {}) => {
		loading.value = true
		error.value = null

		currentFilters.value = { ...currentFilters.value, ...filters }

		try {
			const response = await companyService.getAll(currentFilters.value)
			companies.value = response.data
			meta.value = response.meta
		} catch (err: unknown) {
			error.value = normalizeApiError(err).message || 'Erro ao carregar lista de empresas.'
		} finally {
			loading.value = false
		}
	}

	const changePage = async (page: number) => {
		if (meta.value && page >= 1 && page <= meta.value.last_page) {
			await fetchCompanies({ page })
		}
	}

	const deleteCompany = async (uuid: string) => {
		deleting.value = true
		deleteError.value = null

		const currentPage = currentFilters.value.page ?? 1
		const isLastItemOnPage = companies.value.length === 1 && currentPage > 1
		const nextPage = isLastItemOnPage ? currentPage - 1 : currentPage

		try {
			await companyService.delete(uuid)
			await fetchCompanies({ page: nextPage })
		} catch (err: unknown) {
			deleteError.value = normalizeApiError(err).message || 'Erro ao remover empresa.'
			throw err
		} finally {
			deleting.value = false
		}
	}

	const clearDeleteError = () => {
		deleteError.value = null
	}

	return {
		companies,
		meta,
		loading,
		deleting,
		error,
		deleteError,
		currentFilters,
		fetchCompanies,
		changePage,
		deleteCompany,
		clearDeleteError,
	}
})
