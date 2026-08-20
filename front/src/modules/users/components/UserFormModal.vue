<script setup lang="ts">
import { toRef } from 'vue'
import AppButton from '@/components/AppButton.vue'
import AppModal, { type AppModalSize } from '@/components/ui/AppModal.vue'
import UserForm from './UserForm.vue'
import { useUserForm, type UserFormMode } from '../composables/useUserForm'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: UserFormMode
		userUuid?: string | null
		modalSize?: AppModalSize
		modalMaxWidthClass?: string
	}>(),
	{
		userUuid: null,
		modalSize: '2xl',
		modalMaxWidthClass: undefined,
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
	(event: 'saved'): void
}>()
const { values, errors: fieldErrors, isSubmitting: submitting, loadingUserDetails, globalErrorMessage, avatarPreviewUrl, roleOptions, loadingRoles, updateField, isEditMode, title, submitLabel, handleAvatarChange, submitForm, closeForm } = useUserForm({
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
		:size="modalSize"
		:max-width-class="modalMaxWidthClass"
		@update:modelValue="(nextValue) => (nextValue ? emit('update:modelValue', true) : close())"
	>
		<div class="w-full">
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

			<UserForm
				v-else
				:values="values"
				:field-errors="fieldErrors"
				:submitting="submitting"
				:is-edit-mode="isEditMode"
				:avatar-preview-url="avatarPreviewUrl"
				:global-error-message="globalErrorMessage"
				:role-options="roleOptions"
				:loading-roles="loadingRoles"
				@submit="submitForm"
				@avatar-change="handleAvatarChange"
				@update-field="updateField"
			/>

			<div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
				<AppButton variant="outline" :disabled="submitting || loadingUserDetails" @click="close">
					Cancelar
				</AppButton>
				<AppButton type="submit" form="user-form" :disabled="submitting || loadingUserDetails">
					{{ submitLabel }}
				</AppButton>
			</div>
		</div>
	</AppModal>
</template>
