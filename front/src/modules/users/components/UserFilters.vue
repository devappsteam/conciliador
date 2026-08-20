<script setup lang="ts">
import { onMounted } from 'vue'
import { Search } from '@lucide/vue'
import { useRoleOptions } from '../composables/useRoleOptions'

const props = withDefaults(
	defineProps<{
		search?: string
		roleUuid?: string
	}>(),
	{
		search: '',
		roleUuid: '',
	},
)

const emit = defineEmits<{
	(event: 'update:search', value: string): void
	(event: 'update:role-uuid', value: string): void
	(event: 'apply-filters'): void
}>()

const { roles: roleOptions, fetchRoles } = useRoleOptions()

onMounted(() => {
	void fetchRoles()
})

let debounceTimer: ReturnType<typeof setTimeout>

const onSearchInput = (event: Event) => {
	const target = event.target as HTMLInputElement
	emit('update:search', target.value)

	clearTimeout(debounceTimer)
	debounceTimer = setTimeout(() => {
		emit('apply-filters')
	}, 400)
}

const onRoleChange = (event: Event) => {
	const target = event.target as HTMLSelectElement
	emit('update:role-uuid', target.value)
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
				placeholder="Buscar por nome ou e-mail..."
				class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
			/>
		</div>

		<div class="flex items-center gap-2 w-full md:w-auto justify-end">
			<select
				:value="props.roleUuid"
				@change="onRoleChange"
				class="px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
			>
				<option value="">Todos os Perfis</option>
				<option v-for="option in roleOptions" :key="option.uuid" :value="option.uuid">
					{{ option.name }}
				</option>
			</select>
		</div>
	</div>
</template>
