<template>
	<div class="space-y-6">
		<AcquirerHeader @create="openCreateModal" />

		<AcquirerFilters
			:search="acquirerStore.currentFilters.search"
			:status="acquirerStore.currentFilters.status"
			@update:search="handleSearchChange"
			@update:status="handleStatusChange"
			@apply-filters="applyFilters"
		/>

		<div v-if="acquirerStore.error" class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
			{{ acquirerStore.error }}
		</div>

		<div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
			<AcquirerTable
				:acquirers="acquirerStore.acquirers"
				:loading="acquirerStore.loading"
				:toggling-uuid="acquirerStore.togglingUuid"
				@edit="editAcquirer"
				@delete="openDeleteModal"
				@toggle-status="acquirerStore.toggleStatus"
			/>

			<AppPagination :meta="acquirerStore.meta" :loading="acquirerStore.loading" @change-page="acquirerStore.changePage" />
		</div>

		<AcquirerDeleteModal
			v-model="isDeleteModalOpen"
			:acquirer="selectedAcquirerForDelete"
			:loading="acquirerStore.deleting"
			:error-message="acquirerStore.deleteError"
			@confirm="confirmDeleteAcquirer"
		/>

		<AcquirerFormModal
			v-model="isAcquirerFormModalOpen"
			:mode="acquirerFormMode"
			:acquirer="selectedAcquirerForEdit"
			@saved="handleAcquirerSaved"
		/>
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import AppPagination from '@/components/AppPagination.vue'
import { useAcquirerStore } from '../stores/useAcquirerStore'
import type { Acquirer, AcquirerFilters as AcquirerFiltersType } from '../types'
import AcquirerDeleteModal from '../components/AcquirerDeleteModal.vue'
import AcquirerFilters from '../components/AcquirerFilters.vue'
import AcquirerFormModal from '../components/AcquirerFormModal.vue'
import AcquirerHeader from '../components/AcquirerHeader.vue'
import AcquirerTable from '../components/AcquirerTable.vue'

const acquirerStore = useAcquirerStore()
const isDeleteModalOpen = ref(false)
const selectedAcquirerForDelete = ref<Acquirer | null>(null)
const isAcquirerFormModalOpen = ref(false)
const acquirerFormMode = ref<'create' | 'edit'>('create')
const selectedAcquirerForEdit = ref<Acquirer | null>(null)

const handleSearchChange = (value: string) => {
	acquirerStore.currentFilters.search = value
}

const handleStatusChange = (value: string) => {
	acquirerStore.currentFilters.status = value as AcquirerFiltersType['status']
}

const applyFilters = () => {
	acquirerStore.fetchAcquirers({ page: 1 })
}

const openCreateModal = () => {
	acquirerFormMode.value = 'create'
	selectedAcquirerForEdit.value = null
	isAcquirerFormModalOpen.value = true
}

const editAcquirer = (acquirer: Acquirer) => {
	acquirerFormMode.value = 'edit'
	selectedAcquirerForEdit.value = acquirer
	isAcquirerFormModalOpen.value = true
}

const handleAcquirerSaved = async () => {
	await acquirerStore.fetchAcquirers({ page: acquirerStore.currentFilters.page ?? 1 })
}

const openDeleteModal = (uuid: string) => {
	const acquirer = acquirerStore.acquirers.find((item) => item.uuid === uuid)

	if (!acquirer) {
		return
	}

	acquirerStore.clearDeleteError()
	selectedAcquirerForDelete.value = acquirer
	isDeleteModalOpen.value = true
}

const confirmDeleteAcquirer = async () => {
	if (!selectedAcquirerForDelete.value) {
		return
	}

	try {
		await acquirerStore.deleteAcquirer(selectedAcquirerForDelete.value.uuid)
		isDeleteModalOpen.value = false
		selectedAcquirerForDelete.value = null
	} catch {
		// Erro já tratado e exposto por acquirerStore.deleteError
	}
}

watch(isDeleteModalOpen, (isOpen) => {
	if (!isOpen && !acquirerStore.deleting) {
		selectedAcquirerForDelete.value = null
		acquirerStore.clearDeleteError()
	}
})

watch(isAcquirerFormModalOpen, (isOpen) => {
	if (!isOpen) {
		acquirerFormMode.value = 'create'
		selectedAcquirerForEdit.value = null
	}
})

onMounted(() => {
	acquirerStore.fetchAcquirers()
})
</script>
