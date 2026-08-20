<template>
	<div class="space-y-6">
		<CompanyHeader @create="openCreateModal" />

		<CompanyFilters
			:search="companyStore.currentFilters.search"
			:status="companyStore.currentFilters.status"
			@update:search="handleSearchChange"
			@update:status="handleStatusChange"
			@apply-filters="applyFilters"
		/>

		<div v-if="companyStore.error" class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
			{{ companyStore.error }}
		</div>

		<div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
			<CompanyTable :companies="companyStore.companies" :loading="companyStore.loading" @edit="editCompany" @delete="openDeleteModal" />

			<AppPagination :meta="companyStore.meta" :loading="companyStore.loading" @change-page="companyStore.changePage" />
		</div>

		<CompanyDeleteModal
			v-model="isDeleteModalOpen"
			:company="selectedCompanyForDelete"
			:loading="companyStore.deleting"
			:error-message="companyStore.deleteError"
			@confirm="confirmDeleteCompany"
		/>

		<CompanyFormModal v-model="isCompanyFormModalOpen" :mode="companyFormMode" :company="selectedCompanyForEdit" @saved="handleCompanySaved" />
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import AppPagination from '@/components/AppPagination.vue'
import { useCompanyStore } from '../stores/useCompanyStore'
import type { Company, CompanyFilters as CompanyFiltersType } from '../types'
import CompanyDeleteModal from '../components/CompanyDeleteModal.vue'
import CompanyFilters from '../components/CompanyFilters.vue'
import CompanyFormModal from '../components/CompanyFormModal.vue'
import CompanyHeader from '../components/CompanyHeader.vue'
import CompanyTable from '../components/CompanyTable.vue'

const companyStore = useCompanyStore()
const isDeleteModalOpen = ref(false)
const selectedCompanyForDelete = ref<Company | null>(null)
const isCompanyFormModalOpen = ref(false)
const companyFormMode = ref<'create' | 'edit'>('create')
const selectedCompanyForEdit = ref<Company | null>(null)

const handleSearchChange = (value: string) => {
	companyStore.currentFilters.search = value
}

const handleStatusChange = (value: string) => {
	companyStore.currentFilters.status = value as CompanyFiltersType['status']
}

const applyFilters = () => {
	companyStore.fetchCompanies({ page: 1 })
}

const openCreateModal = () => {
	companyFormMode.value = 'create'
	selectedCompanyForEdit.value = null
	isCompanyFormModalOpen.value = true
}

const editCompany = (company: Company) => {
	companyFormMode.value = 'edit'
	selectedCompanyForEdit.value = company
	isCompanyFormModalOpen.value = true
}

const handleCompanySaved = async () => {
	await companyStore.fetchCompanies({ page: companyStore.currentFilters.page ?? 1 })
}

const openDeleteModal = (uuid: string) => {
	const company = companyStore.companies.find((item) => item.uuid === uuid)

	if (!company) {
		return
	}

	companyStore.clearDeleteError()
	selectedCompanyForDelete.value = company
	isDeleteModalOpen.value = true
}

const confirmDeleteCompany = async () => {
	if (!selectedCompanyForDelete.value) {
		return
	}

	try {
		await companyStore.deleteCompany(selectedCompanyForDelete.value.uuid)
		isDeleteModalOpen.value = false
		selectedCompanyForDelete.value = null
	} catch {
		// Erro já tratado e exposto por companyStore.deleteError
	}
}

watch(isDeleteModalOpen, (isOpen) => {
	if (!isOpen && !companyStore.deleting) {
		selectedCompanyForDelete.value = null
		companyStore.clearDeleteError()
	}
})

watch(isCompanyFormModalOpen, (isOpen) => {
	if (!isOpen) {
		companyFormMode.value = 'create'
		selectedCompanyForEdit.value = null
	}
})

onMounted(() => {
	companyStore.fetchCompanies()
})
</script>
