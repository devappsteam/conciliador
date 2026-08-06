<script setup lang="ts">
import { USER_ROLE_OPTIONS } from '../constants/user.constants'
import UserAvatarField from './UserAvatarField.vue'
import type { UserFormValues } from '../types'

const props = withDefaults(
	defineProps<{
		values: UserFormValues
		fieldErrors: Partial<Record<keyof UserFormValues, string>>
		submitting: boolean
		isEditMode: boolean
		avatarPreviewUrl: string
		globalErrorMessage: string
		formId?: string
	}>(),
	{
		formId: 'user-form',
	},
)

const emit = defineEmits<{
	(event: 'submit'): void
	(event: 'avatar-change', value: Event): void
	(event: 'update-field', field: keyof UserFormValues, value: UserFormValues[keyof UserFormValues]): void
}>()
</script>

<template>
	<form :id="props.formId" class="space-y-4 p-6" novalidate @submit.prevent="emit('submit')">
		<div v-if="globalErrorMessage" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert">
			{{ globalErrorMessage }}
		</div>

		<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<label for="user-name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Nome
				</label>
				<input
					id="user-name"
					:value="values.name"
					@input="emit('update-field', 'name', ($event.target as HTMLInputElement).value)"
					type="text"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.name ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="Nome completo"
					:aria-invalid="Boolean(fieldErrors.name)"
					:aria-describedby="fieldErrors.name ? 'user-name-error' : undefined"
				/>
				<p v-if="fieldErrors.name" id="user-name-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.name }}
				</p>
			</div>

			<div>
				<label for="user-email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					E-mail
				</label>
				<input
					id="user-email"
					:value="values.email"
					@input="emit('update-field', 'email', ($event.target as HTMLInputElement).value)"
					type="email"
					autocomplete="email"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.email ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="utilizador@empresa.com"
					:aria-invalid="Boolean(fieldErrors.email)"
					:aria-describedby="fieldErrors.email ? 'user-email-error' : undefined"
				/>
				<p v-if="fieldErrors.email" id="user-email-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.email }}
				</p>
			</div>

			<div>
				<label for="user-role" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Perfil
				</label>
				<select
					id="user-role"
					:value="values.role"
					@change="emit('update-field', 'role', ($event.target as HTMLSelectElement).value)"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.role ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					:aria-invalid="Boolean(fieldErrors.role)"
					:aria-describedby="fieldErrors.role ? 'user-role-error' : undefined"
				>
					<option value="">Selecione</option>
					<option v-for="option in USER_ROLE_OPTIONS" :key="option.value" :value="option.value">
						{{ option.label }}
					</option>
				</select>
				<p v-if="fieldErrors.role" id="user-role-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.role }}
				</p>
			</div>

			<div>
				<label for="user-password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					{{ isEditMode ? 'Nova senha (opcional)' : 'Senha' }}
				</label>
				<input
					id="user-password"
					:value="values.password"
					@input="emit('update-field', 'password', ($event.target as HTMLInputElement).value)"
					type="password"
					autocomplete="new-password"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.password ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="••••••••"
					:aria-invalid="Boolean(fieldErrors.password)"
					:aria-describedby="fieldErrors.password ? 'user-password-error' : undefined"
				/>
				<p v-if="fieldErrors.password" id="user-password-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.password }}
				</p>
			</div>

			<div>
				<label for="user-password-confirm" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Confirmar senha
				</label>
				<input
					id="user-password-confirm"
					:value="values.password_confirmation"
					@input="emit('update-field', 'password_confirmation', ($event.target as HTMLInputElement).value)"
					type="password"
					autocomplete="new-password"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.password_confirmation ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="••••••••"
					:aria-invalid="Boolean(fieldErrors.password_confirmation)"
					:aria-describedby="fieldErrors.password_confirmation ? 'user-password-confirm-error' : undefined"
				/>
				<p v-if="fieldErrors.password_confirmation" id="user-password-confirm-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.password_confirmation }}
				</p>
			</div>

			<UserAvatarField
				:preview-url="avatarPreviewUrl"
				:error-message="fieldErrors.avatar"
				:submitting="submitting"
				@change="emit('avatar-change', $event)"
			/>
		</div>
	</form>
</template>
