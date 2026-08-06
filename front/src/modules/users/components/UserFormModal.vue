<script setup lang="ts">
import { toRef } from 'vue'
import AppButton from '@/components/AppButton.vue'
import AppModal from '@/components/ui/AppModal.vue'
import { USER_ROLE_OPTIONS } from '../constants/user.constants'
import { useUserForm, type UserFormMode } from '../composables/useUserForm'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: UserFormMode
		userUuid?: string | null
	}>(),
	{
		userUuid: null,
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
	(event: 'saved'): void
}>()
const { values, errors: fieldErrors, isSubmitting: submitting, loadingUserDetails, globalErrorMessage, avatarPreviewUrl, isEditMode, title, submitLabel, handleAvatarChange, submitForm, closeForm } = useUserForm({
	isOpen: toRef(props, 'modelValue'),
	mode: toRef(props, 'mode'),
	userUuid: toRef(props, 'userUuid'),
	onSaved: async () => {
		emit('saved')
		emit('update:modelValue', false)
	},
})

const close = () => {
	if (submitting.value || loadingUserDetails.value) {
		return
	}

	closeForm()
	emit('update:modelValue', false)
}
</script>
<template>
	<AppModal
		:model-value="modelValue"
		title-id="user-form-title"
		description-id="user-form-description"
		@update:modelValue="emit('update:modelValue', $event)"
	>
		<div class="border-b border-gray-100 p-6 dark:border-gray-700">
			<h2 id="user-form-title" class="text-lg font-semibold text-gray-900 dark:text-white">
				{{ title }}
			</h2>
			<p id="user-form-description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
				{{
					isEditMode
						? 'Atualize os dados do utilizador e salve as alterações.'
						: 'Preencha os dados para cadastrar um novo utilizador.'
				}}
			</p>
		</div>

		<div v-if="loadingUserDetails" class="space-y-4 p-6 animate-pulse">
			<div class="h-4 w-44 rounded bg-gray-200 dark:bg-gray-700"></div>
			<div class="h-10 rounded bg-gray-200 dark:bg-gray-700"></div>
			<div class="h-4 w-44 rounded bg-gray-200 dark:bg-gray-700"></div>
			<div class="h-10 rounded bg-gray-200 dark:bg-gray-700"></div>
			<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
				<div>
					<div class="mb-2 h-4 w-32 rounded bg-gray-200 dark:bg-gray-700"></div>
					<div class="h-10 rounded bg-gray-200 dark:bg-gray-700"></div>
				</div>
				<div>
					<div class="mb-2 h-4 w-32 rounded bg-gray-200 dark:bg-gray-700"></div>
					<div class="h-10 rounded bg-gray-200 dark:bg-gray-700"></div>
				</div>
			</div>
		</div>

		<form id="user-form" v-else @submit.prevent="submitForm" class="space-y-4 p-6" novalidate>
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
						v-model="values.name"
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
						v-model="values.email"
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
						v-model="values.role"
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
						v-model="values.password"
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
						v-model="values.password_confirmation"
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

				<div class="md:col-span-2">
					<label for="user-avatar" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
						Avatar
					</label>

					<div class="flex items-center gap-4">
						<div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-gray-100 text-xs text-gray-500 dark:bg-gray-700">
							<img v-if="avatarPreviewUrl" :src="avatarPreviewUrl" alt="Pré-visualização do avatar" class="h-full w-full object-cover" />
							<span v-else>Sem foto</span>
						</div>

						<input
							id="user-avatar"
							type="file"
							accept="image/*"
							:disabled="submitting"
							class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-gray-200 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-800 hover:file:bg-gray-300 dark:text-gray-300"
							@change="handleAvatarChange"
						/>
					</div>

					<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
						Formatos aceitos: imagem. Tamanho máximo: 2MB.
					</p>
					<p v-if="fieldErrors.avatar" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
						{{ fieldErrors.avatar }}
					</p>
				</div>
			</div>
		</form>

		<div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
			<AppButton variant="outline" :disabled="submitting || loadingUserDetails" @click="close">
				Cancelar
			</AppButton>
			<AppButton type="submit" form="user-form" :disabled="submitting || loadingUserDetails">
				{{ submitLabel }}
			</AppButton>
		</div>
	</AppModal>
</template>
