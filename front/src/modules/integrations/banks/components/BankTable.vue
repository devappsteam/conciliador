<script setup lang="ts">
import { Edit, Trash } from '@lucide/vue'
import AppButton from '@/components/AppButton.vue'
import type { Bank } from '../types'
import BankEmptyState from './BankEmptyState.vue'
import BankStatusBadge from './BankStatusBadge.vue'
import BankTableSkeleton from './BankTableSkeleton.vue'

defineProps<{
	banks: Bank[]
	loading: boolean
	togglingUuid: string | null
}>()

const emit = defineEmits<{
	(event: 'edit', bank: Bank): void
	(event: 'delete', uuid: string): void
	(event: 'toggle-status', uuid: string): void
}>()

const formatDate = (date?: string) => (date ? new Date(date).toLocaleDateString('pt-BR') : '--')
</script>

<template>
	<div class="overflow-x-auto">
		<table class="w-full text-left border-collapse">
			<thead>
				<tr class="bg-gray-50 dark:bg-gray-900 text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700">
					<th class="px-6 py-4">Banco</th>
					<th class="px-6 py-4">Código</th>
					<th class="px-6 py-4">Status</th>
					<th class="px-6 py-4">Atualizado em</th>
					<th class="px-6 py-4 text-right">Ações</th>
				</tr>
			</thead>

			<tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
				<BankTableSkeleton v-if="loading" />

				<template v-else-if="banks.length > 0">
					<tr v-for="bank in banks" :key="bank.uuid" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
						<td class="px-6 py-4">
							<div class="flex items-center gap-3">
								<div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0">
									<img v-if="bank.logo" :src="bank.logo" :alt="bank.name" class="w-full h-full object-contain" />
									<span v-else class="text-xs font-semibold text-gray-400">{{ bank.name.slice(0, 2).toUpperCase() }}</span>
								</div>
								<p class="font-medium text-gray-900 dark:text-white">{{ bank.name }}</p>
							</div>
						</td>

						<td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ bank.code }}</td>

						<td class="px-6 py-4">
							<button
								type="button"
								:disabled="togglingUuid === bank.uuid"
								class="disabled:opacity-50 disabled:cursor-not-allowed"
								@click="emit('toggle-status', bank.uuid)"
							>
								<BankStatusBadge :active="bank.status" />
							</button>
						</td>

						<td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ formatDate(bank.updated_at) }}</td>

						<td class="px-6 py-4 text-right whitespace-nowrap">
							<AppButton variant="ghost" size="icon" class="mr-1 text-blue-600 hover:text-blue-900" @click="emit('edit', bank)">
								<template #icon>
									<Edit class="w-4 h-4" />
								</template>
							</AppButton>

							<AppButton variant="ghost" size="icon" class="text-red-600 hover:text-red-900" @click="emit('delete', bank.uuid)">
								<template #icon>
									<Trash class="w-4 h-4" />
								</template>
							</AppButton>
						</td>
					</tr>
				</template>

				<BankEmptyState v-else :colspan="5" />
			</tbody>
		</table>
	</div>
</template>
