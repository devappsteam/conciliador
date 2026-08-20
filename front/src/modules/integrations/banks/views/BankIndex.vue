<template>
	<div class="space-y-6">
		<BankHeader @create="openCreateModal" />

		<BankFilters
			:search="bankStore.currentFilters.search"
			:status="bankStore.currentFilters.status"
			@update:search="handleSearchChange"
			@update:status="handleStatusChange"
			@apply-filters="applyFilters"
		/>

		<div v-if="bankStore.error" class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
			{{ bankStore.error }}
		</div>

		<div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
			<BankTable
				:banks="bankStore.banks"
				:loading="bankStore.loading"
				:toggling-uuid="bankStore.togglingUuid"
				@edit="editBank"
				@delete="openDeleteModal"
				@toggle-status="bankStore.toggleStatus"
			/>

			<AppPagination :meta="bankStore.meta" :loading="bankStore.loading" @change-page="bankStore.changePage" />
		</div>

		<BankDeleteModal
			v-model="isDeleteModalOpen"
			:bank="selectedBankForDelete"
			:loading="bankStore.deleting"
			:error-message="bankStore.deleteError"
			@confirm="confirmDeleteBank"
		/>

		<BankFormModal v-model="isBankFormModalOpen" :mode="bankFormMode" :bank="selectedBankForEdit" @saved="handleBankSaved" />
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import AppPagination from '@/components/AppPagination.vue'
import { useBankStore } from '../stores/useBankStore'
import type { Bank, BankFilters as BankFiltersType } from '../types'
import BankDeleteModal from '../components/BankDeleteModal.vue'
import BankFilters from '../components/BankFilters.vue'
import BankFormModal from '../components/BankFormModal.vue'
import BankHeader from '../components/BankHeader.vue'
import BankTable from '../components/BankTable.vue'

const bankStore = useBankStore()
const isDeleteModalOpen = ref(false)
const selectedBankForDelete = ref<Bank | null>(null)
const isBankFormModalOpen = ref(false)
const bankFormMode = ref<'create' | 'edit'>('create')
const selectedBankForEdit = ref<Bank | null>(null)

const handleSearchChange = (value: string) => {
	bankStore.currentFilters.search = value
}

const handleStatusChange = (value: string) => {
	bankStore.currentFilters.status = value as BankFiltersType['status']
}

const applyFilters = () => {
	bankStore.fetchBanks({ page: 1 })
}

const openCreateModal = () => {
	bankFormMode.value = 'create'
	selectedBankForEdit.value = null
	isBankFormModalOpen.value = true
}

const editBank = (bank: Bank) => {
	bankFormMode.value = 'edit'
	selectedBankForEdit.value = bank
	isBankFormModalOpen.value = true
}

const handleBankSaved = async () => {
	await bankStore.fetchBanks({ page: bankStore.currentFilters.page ?? 1 })
}

const openDeleteModal = (uuid: string) => {
	const bank = bankStore.banks.find((item) => item.uuid === uuid)

	if (!bank) {
		return
	}

	bankStore.clearDeleteError()
	selectedBankForDelete.value = bank
	isDeleteModalOpen.value = true
}

const confirmDeleteBank = async () => {
	if (!selectedBankForDelete.value) {
		return
	}

	try {
		await bankStore.deleteBank(selectedBankForDelete.value.uuid)
		isDeleteModalOpen.value = false
		selectedBankForDelete.value = null
	} catch {
		// Erro já tratado e exposto por bankStore.deleteError
	}
}

watch(isDeleteModalOpen, (isOpen) => {
	if (!isOpen && !bankStore.deleting) {
		selectedBankForDelete.value = null
		bankStore.clearDeleteError()
	}
})

watch(isBankFormModalOpen, (isOpen) => {
	if (!isOpen) {
		bankFormMode.value = 'create'
		selectedBankForEdit.value = null
	}
})

onMounted(() => {
	bankStore.fetchBanks()
})
</script>
