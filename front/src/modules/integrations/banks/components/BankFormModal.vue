<script setup lang="ts">
import { toRef } from 'vue'
import AppButton from '@/components/AppButton.vue'
import AppModal, { type AppModalSize } from '@/components/ui/AppModal.vue'
import BankForm from './BankForm.vue'
import { useBankForm, type BankFormMode } from '../composables/useBankForm'
import type { Bank } from '../types'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: BankFormMode
		bank?: Bank | null
		modalSize?: AppModalSize
	}>(),
	{
		bank: null,
		modalSize: '2xl',
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
	(event: 'saved'): void
}>()

const {
	values,
	errors: fieldErrors,
	isSubmitting: submitting,
	globalErrorMessage,
	logoPreviewUrl,
	updateField,
	isEditMode,
	title,
	submitLabel,
	handleLogoChange,
	submitForm,
	closeForm,
} = useBankForm({
	isOpen: toRef(props, 'modelValue'),
	mode: toRef(props, 'mode'),
	bank: toRef(props, 'bank'),
	onSaved: async () => {
		emit('saved')
		emit('update:modelValue', false)
	},
})

const close = () => {
	if (submitting.value) {
		return
	}

	closeForm()
	emit('update:modelValue', false)
}
</script>
<template>
	<AppModal
		:model-value="modelValue"
		title-id="bank-form-title"
		description-id="bank-form-description"
		:size="modalSize"
		@update:modelValue="(nextValue) => (nextValue ? emit('update:modelValue', true) : close())"
	>
		<div class="w-full">
			<div class="border-b border-gray-100 p-6 dark:border-gray-700">
				<h2 id="bank-form-title" class="text-lg font-semibold text-gray-900 dark:text-white">
					{{ title }}
				</h2>
				<p id="bank-form-description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
					{{
						isEditMode
							? 'Atualize os dados do banco e salve as alterações.'
							: 'Preencha os dados para cadastrar um novo banco.'
					}}
				</p>
			</div>

			<BankForm
				:values="values"
				:field-errors="fieldErrors"
				:submitting="submitting"
				:is-edit-mode="isEditMode"
				:logo-preview-url="logoPreviewUrl"
				:global-error-message="globalErrorMessage"
				@submit="submitForm"
				@logo-change="handleLogoChange"
				@update-field="updateField"
			/>

			<div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
				<AppButton variant="outline" :disabled="submitting" @click="close">Cancelar</AppButton>
				<AppButton type="submit" form="bank-form" :disabled="submitting">
					{{ submitLabel }}
				</AppButton>
			</div>
		</div>
	</AppModal>
</template>
