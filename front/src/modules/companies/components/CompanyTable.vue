<script setup lang="ts">
import { Edit, Trash } from '@lucide/vue'
import AppButton from '@/components/AppButton.vue'
import type { Company } from '../types'
import { formatCnpj } from '../utils/document.utils'
import CompanyEmptyState from './CompanyEmptyState.vue'
import CompanyStatusBadge from './CompanyStatusBadge.vue'
import CompanyTableSkeleton from './CompanyTableSkeleton.vue'

defineProps<{
	companies: Company[]
	loading: boolean
}>()

const emit = defineEmits<{
	(event: 'edit', company: Company): void
	(event: 'delete', uuid: string): void
}>()

const formatDate = (date?: string) => (date ? new Date(date).toLocaleDateString('pt-BR') : '--')
</script>

<template>
	<div class="overflow-x-auto">
		<table class="w-full text-left border-collapse">
			<thead>
				<tr class="bg-gray-50 dark:bg-gray-900 text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700">
					<th class="px-6 py-4">Empresa</th>
					<th class="px-6 py-4">CNPJ</th>
					<th class="px-6 py-4">Status</th>
					<th class="px-6 py-4">Cadastrada em</th>
					<th class="px-6 py-4 text-right">Ações</th>
				</tr>
			</thead>

			<tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
				<CompanyTableSkeleton v-if="loading" />

				<template v-else-if="companies.length > 0">
					<tr v-for="company in companies" :key="company.uuid" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
						<td class="px-6 py-4">
							<p class="font-medium text-gray-900 dark:text-white">{{ company.corporate_name }}</p>
							<p v-if="company.trade_name" class="text-xs text-gray-400">{{ company.trade_name }}</p>
						</td>

						<td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ formatCnpj(company.document) }}</td>

						<td class="px-6 py-4">
							<CompanyStatusBadge :status="company.status" />
						</td>

						<td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ formatDate(company.created_at) }}</td>

						<td class="px-6 py-4 text-right whitespace-nowrap">
							<AppButton variant="ghost" size="icon" class="mr-1 text-blue-600 hover:text-blue-900" @click="emit('edit', company)">
								<template #icon>
									<Edit class="w-4 h-4" />
								</template>
							</AppButton>

							<AppButton variant="ghost" size="icon" class="text-red-600 hover:text-red-900" @click="emit('delete', company.uuid)">
								<template #icon>
									<Trash class="w-4 h-4" />
								</template>
							</AppButton>
						</td>
					</tr>
				</template>

				<CompanyEmptyState v-else :colspan="5" />
			</tbody>
		</table>
	</div>
</template>
