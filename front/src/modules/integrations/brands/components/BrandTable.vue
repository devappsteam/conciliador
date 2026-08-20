<script setup lang="ts">
import { Edit, Trash } from '@lucide/vue'
import AppButton from '@/components/AppButton.vue'
import type { Brand } from '../types'
import BrandEmptyState from './BrandEmptyState.vue'
import BrandStatusBadge from './BrandStatusBadge.vue'
import BrandTableSkeleton from './BrandTableSkeleton.vue'

defineProps<{
	brands: Brand[]
	loading: boolean
	togglingUuid: string | null
}>()

const emit = defineEmits<{
	(event: 'edit', brand: Brand): void
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
					<th class="px-6 py-4">Bandeira</th>
					<th class="px-6 py-4">Código</th>
					<th class="px-6 py-4">Status</th>
					<th class="px-6 py-4">Atualizado em</th>
					<th class="px-6 py-4 text-right">Ações</th>
				</tr>
			</thead>

			<tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
				<BrandTableSkeleton v-if="loading" />

				<template v-else-if="brands.length > 0">
					<tr v-for="brand in brands" :key="brand.uuid" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
						<td class="px-6 py-4">
							<div class="flex items-center gap-3">
								<div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0">
									<img v-if="brand.logo" :src="brand.logo" :alt="brand.name" class="w-full h-full object-contain" />
									<span v-else class="text-xs font-semibold text-gray-400">{{ brand.name.slice(0, 2).toUpperCase() }}</span>
								</div>
								<p class="font-medium text-gray-900 dark:text-white">{{ brand.name }}</p>
							</div>
						</td>

						<td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ brand.code }}</td>

						<td class="px-6 py-4">
							<button
								type="button"
								:disabled="togglingUuid === brand.uuid"
								class="disabled:opacity-50 disabled:cursor-not-allowed"
								@click="emit('toggle-status', brand.uuid)"
							>
								<BrandStatusBadge :active="brand.status" />
							</button>
						</td>

						<td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ formatDate(brand.updated_at) }}</td>

						<td class="px-6 py-4 text-right whitespace-nowrap">
							<AppButton variant="ghost" size="icon" class="mr-1 text-blue-600 hover:text-blue-900" @click="emit('edit', brand)">
								<template #icon>
									<Edit class="w-4 h-4" />
								</template>
							</AppButton>

							<AppButton variant="ghost" size="icon" class="text-red-600 hover:text-red-900" @click="emit('delete', brand.uuid)">
								<template #icon>
									<Trash class="w-4 h-4" />
								</template>
							</AppButton>
						</td>
					</tr>
				</template>

				<BrandEmptyState v-else :colspan="5" />
			</tbody>
		</table>
	</div>
</template>
