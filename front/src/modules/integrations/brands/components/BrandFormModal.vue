<script setup lang="ts">
import { toRef } from 'vue'
import AppButton from '@/components/AppButton.vue'
import AppModal, { type AppModalSize } from '@/components/ui/AppModal.vue'
import BrandForm from './BrandForm.vue'
import { useBrandForm, type BrandFormMode } from '../composables/useBrandForm'
import type { Brand } from '../types'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: BrandFormMode
		brand?: Brand | null
		modalSize?: AppModalSize
	}>(),
	{
		brand: null,
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
} = useBrandForm({
	isOpen: toRef(props, 'modelValue'),
	mode: toRef(props, 'mode'),
	brand: toRef(props, 'brand'),
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
		title-id="brand-form-title"
		description-id="brand-form-description"
		:size="modalSize"
		@update:modelValue="(nextValue) => (nextValue ? emit('update:modelValue', true) : close())"
	>
		<div class="w-full">
			<div class="border-b border-gray-100 p-6 dark:border-gray-700">
				<h2 id="brand-form-title" class="text-lg font-semibold text-gray-900 dark:text-white">
					{{ title }}
				</h2>
				<p id="brand-form-description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
					{{
						isEditMode
							? 'Atualize os dados da bandeira e salve as alterações.'
							: 'Preencha os dados para cadastrar uma nova bandeira.'
					}}
				</p>
			</div>

			<BrandForm
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
				<AppButton type="submit" form="brand-form" :disabled="submitting">
					{{ submitLabel }}
				</AppButton>
			</div>
		</div>
	</AppModal>
</template>
