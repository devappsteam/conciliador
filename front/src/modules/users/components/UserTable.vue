<script setup lang="ts">
import { Edit, Trash } from '@lucide/vue'
import AppButton from '@/components/AppButton.vue'
import type { User } from '../types'
import UserAvatar from './UserAvatar.vue'
import UserEmptyState from './UserEmptyState.vue'
import UserRoleBadge from './UserRoleBadge.vue'
import UserTableSkeleton from './UserTableSkeleton.vue'

defineProps<{
	users: User[]
	loading: boolean
}>()

const emit = defineEmits<{
	(event: 'edit', user: User): void
	(event: 'delete', uuid: string): void
}>()

const formatDate = (date: string) => new Date(date).toLocaleDateString('pt-PT')
</script>

<template>
	<div class="overflow-x-auto">
		<table class="w-full text-left border-collapse">
			<thead>
				<tr
					class="bg-gray-50 dark:bg-gray-900 text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700"
				>
					<th class="px-6 py-4">Usuário</th>
					<th class="px-6 py-4">Perfil</th>
					<th class="px-6 py-4">Data de Cadastro</th>
					<th class="px-6 py-4 text-right">Ações</th>
				</tr>
			</thead>

			<tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
				<UserTableSkeleton v-if="loading" />

				<template v-else-if="users.length > 0">
					<tr
						v-for="user in users"
						:key="user.uuid"
						class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors"
					>
						<td class="px-6 py-4">
							<UserAvatar :name="user.name" :email="user.email" :avatar-url="user.avatar_url" />
						</td>

						<td class="px-6 py-4">
							<UserRoleBadge :role="user.role?.[0]?.name" />
						</td>

						<td class="px-6 py-4 text-gray-500 dark:text-gray-400">
							{{ formatDate(user.created_at) }}
						</td>

						<td class="px-6 py-4 text-right whitespace-nowrap">
							<AppButton
								variant="ghost"
								size="icon"
								class="mr-1 text-blue-600 hover:text-blue-900"
								@click="emit('edit', user)"
							>
								<template #icon>
									<Edit class="w-4 h-4" />
								</template>
							</AppButton>

							<AppButton
								variant="ghost"
								size="icon"
								class="text-red-600 hover:text-red-900"
								@click="emit('delete', user.uuid)"
							>
								<template #icon>
									<Trash class="w-4 h-4" />
								</template>
							</AppButton>
						</td>
					</tr>
				</template>

				<UserEmptyState v-else />
			</tbody>
		</table>
	</div>
</template>
