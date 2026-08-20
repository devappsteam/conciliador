<template>
	<div class="space-y-6">
		<BrandHeader @create="openCreateModal" />

		<BrandFilters
			:search="brandStore.currentFilters.search"
			:status="brandStore.currentFilters.status"
			@update:search="handleSearchChange"
			@update:status="handleStatusChange"
			@apply-filters="applyFilters"
		/>

		<div v-if="brandStore.error" class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
			{{ brandStore.error }}
		</div>

		<div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
			<BrandTable
				:brands="brandStore.brands"
				:loading="brandStore.loading"
				:toggling-uuid="brandStore.togglingUuid"
				@edit="editBrand"
				@delete="openDeleteModal"
				@toggle-status="brandStore.toggleStatus"
			/>

			<AppPagination :meta="brandStore.meta" :loading="brandStore.loading" @change-page="brandStore.changePage" />
		</div>

		<BrandDeleteModal
			v-model="isDeleteModalOpen"
			:brand="selectedBrandForDelete"
			:loading="brandStore.deleting"
			:error-message="brandStore.deleteError"
			@confirm="confirmDeleteBrand"
		/>

		<BrandFormModal v-model="isBrandFormModalOpen" :mode="brandFormMode" :brand="selectedBrandForEdit" @saved="handleBrandSaved" />
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import AppPagination from '@/components/AppPagination.vue'
import { useBrandStore } from '../stores/useBrandStore'
import type { Brand, BrandFilters as BrandFiltersType } from '../types'
import BrandDeleteModal from '../components/BrandDeleteModal.vue'
import BrandFilters from '../components/BrandFilters.vue'
import BrandFormModal from '../components/BrandFormModal.vue'
import BrandHeader from '../components/BrandHeader.vue'
import BrandTable from '../components/BrandTable.vue'

const brandStore = useBrandStore()
const isDeleteModalOpen = ref(false)
const selectedBrandForDelete = ref<Brand | null>(null)
const isBrandFormModalOpen = ref(false)
const brandFormMode = ref<'create' | 'edit'>('create')
const selectedBrandForEdit = ref<Brand | null>(null)

const handleSearchChange = (value: string) => {
	brandStore.currentFilters.search = value
}

const handleStatusChange = (value: string) => {
	brandStore.currentFilters.status = value as BrandFiltersType['status']
}

const applyFilters = () => {
	brandStore.fetchBrands({ page: 1 })
}

const openCreateModal = () => {
	brandFormMode.value = 'create'
	selectedBrandForEdit.value = null
	isBrandFormModalOpen.value = true
}

const editBrand = (brand: Brand) => {
	brandFormMode.value = 'edit'
	selectedBrandForEdit.value = brand
	isBrandFormModalOpen.value = true
}

const handleBrandSaved = async () => {
	await brandStore.fetchBrands({ page: brandStore.currentFilters.page ?? 1 })
}

const openDeleteModal = (uuid: string) => {
	const brand = brandStore.brands.find((item) => item.uuid === uuid)

	if (!brand) {
		return
	}

	brandStore.clearDeleteError()
	selectedBrandForDelete.value = brand
	isDeleteModalOpen.value = true
}

const confirmDeleteBrand = async () => {
	if (!selectedBrandForDelete.value) {
		return
	}

	try {
		await brandStore.deleteBrand(selectedBrandForDelete.value.uuid)
		isDeleteModalOpen.value = false
		selectedBrandForDelete.value = null
	} catch {
		// Erro já tratado e exposto por brandStore.deleteError
	}
}

watch(isDeleteModalOpen, (isOpen) => {
	if (!isOpen && !brandStore.deleting) {
		selectedBrandForDelete.value = null
		brandStore.clearDeleteError()
	}
})

watch(isBrandFormModalOpen, (isOpen) => {
	if (!isOpen) {
		brandFormMode.value = 'create'
		selectedBrandForEdit.value = null
	}
})

onMounted(() => {
	brandStore.fetchBrands()
})
</script>
