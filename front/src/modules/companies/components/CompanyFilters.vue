<script setup lang="ts">
import { Search } from '@lucide/vue'
import { COMPANY_STATUS_OPTIONS } from '../constants/company.constants'

const props = withDefaults(
	defineProps<{
		search?: string
		status?: string
	}>(),
	{
		search: '',
		status: '',
	},
)

const emit = defineEmits<{
	(event: 'update:search', value: string): void
	(event: 'update:status', value: string): void
	(event: 'apply-filters'): void
}>()

let debounceTimer: ReturnType<typeof setTimeout>

const onSearchInput = (event: Event) => {
	const target = event.target as HTMLInputElement
	emit('update:search', target.value)

	clearTimeout(debounceTimer)
	debounceTimer = setTimeout(() => {
		emit('apply-filters')
	}, 400)
}

const onStatusChange = (event: Event) => {
	const target = event.target as HTMLSelectElement
	emit('update:status', target.value)
	emit('apply-filters')
}
</script>

<template>
	<div
		class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between"
	>
		<div class="w-full md:w-96 relative">
			<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
				<Search class="w-5 h-5" />
			</span>
			<input
				:value="props.search"
				@input="onSearchInput"
				type="text"
				placeholder="Buscar por razão social, nome fantasia ou CNPJ..."
				class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
			/>
		</div>

		<div class="flex items-center gap-2 w-full md:w-auto justify-end">
			<select
				:value="props.status"
				@change="onStatusChange"
				class="px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
			>
				<option value="">Todos os status</option>
				<option v-for="option in COMPANY_STATUS_OPTIONS" :key="option.value" :value="option.value">
					{{ option.label }}
				</option>
			</select>
		</div>
	</div>
</template>
