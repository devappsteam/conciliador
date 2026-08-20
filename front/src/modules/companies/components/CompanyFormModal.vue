<script setup lang="ts">
import { toRef } from 'vue'
import AppButton from '@/components/AppButton.vue'
import AppModal, { type AppModalSize } from '@/components/ui/AppModal.vue'
import CompanyForm from './CompanyForm.vue'
import { useCompanyForm, type CompanyFormMode } from '../composables/useCompanyForm'
import type { Company } from '../types'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: CompanyFormMode
		company?: Company | null
		modalSize?: AppModalSize
	}>(),
	{
		company: null,
		modalSize: '3xl',
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
	loadingCep,
	globalErrorMessage,
	updateField,
	handleCepBlur,
	isEditMode,
	title,
	submitLabel,
	submitForm,
	closeForm,
} = useCompanyForm({
	isOpen: toRef(props, 'modelValue'),
	mode: toRef(props, 'mode'),
	company: toRef(props, 'company'),
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
		title-id="company-form-title"
		description-id="company-form-description"
		:size="modalSize"
		@update:modelValue="(nextValue) => (nextValue ? emit('update:modelValue', true) : close())"
	>
		<div class="w-full">
			<div class="border-b border-gray-100 p-6 dark:border-gray-700">
				<h2 id="company-form-title" class="text-lg font-semibold text-gray-900 dark:text-white">
					{{ title }}
				</h2>
				<p id="company-form-description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
					{{
						isEditMode
							? 'Atualize os dados da empresa e salve as alterações.'
							: 'Preencha os dados para cadastrar uma nova empresa.'
					}}
				</p>
			</div>

			<div class="max-h-[65vh] overflow-y-auto">
				<CompanyForm
					:values="values"
					:field-errors="fieldErrors"
					:submitting="submitting"
					:is-edit-mode="isEditMode"
					:loading-cep="loadingCep"
					:global-error-message="globalErrorMessage"
					@submit="submitForm"
					@cep-blur="handleCepBlur"
					@update-field="updateField"
				/>
			</div>

			<div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
				<AppButton variant="outline" :disabled="submitting" @click="close">Cancelar</AppButton>
				<AppButton type="submit" form="company-form" :disabled="submitting">
					{{ submitLabel }}
				</AppButton>
			</div>
		</div>
	</AppModal>
</template>
