import { computed, ref, watch, type Ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { useFilePreview } from '@/composables/useFilePreview'
import { acquirerService } from '../services/acquirerService'
import { acquirerFormSchema } from '../schemas/acquirerForm.schema'
import type { Acquirer, AcquirerFormValues } from '../types'
import {
	buildAcquirerPayload,
	createAcquirerFormValuesFromAcquirer,
	createInitialAcquirerFormValues,
	slugify,
} from '../utils/acquirer.utils'
import { normalizeApiError } from '../../utils/normalizeApiError'

export type AcquirerFormMode = 'create' | 'edit'

export interface UseAcquirerFormOptions {
	isOpen: Ref<boolean>
	mode: Ref<AcquirerFormMode>
	acquirer: Ref<Acquirer | null>
	onSaved?: () => void | Promise<void>
}

export const useAcquirerForm = ({ isOpen, mode, acquirer, onSaved }: UseAcquirerFormOptions) => {
	const globalErrorMessage = ref('')
	const logoPreview = useFilePreview('')
	const slugTouchedManually = ref(false)

	const validationSchema = computed(() => toTypedSchema(acquirerFormSchema))

	const { values, errors, handleSubmit, setErrors, setFieldValue, resetForm, isSubmitting } = useForm<AcquirerFormValues>({
		validationSchema,
		initialValues: createInitialAcquirerFormValues(),
	})

	const fieldErrors = computed(() => errors.value as Partial<Record<keyof AcquirerFormValues, string>>)
	const submitting = computed(() => isSubmitting.value)
	const isEditMode = computed(() => mode.value === 'edit')
	const title = computed(() => (isEditMode.value ? 'Editar adquirente' : 'Nova adquirente'))
	const submitLabel = computed(() => {
		if (submitting.value) {
			return isEditMode.value ? 'A guardar...' : 'A cadastrar...'
		}

		return isEditMode.value ? 'Salvar alterações' : 'Cadastrar adquirente'
	})
	const logoPreviewUrl = computed(() => logoPreview.previewUrl.value)

	const clearFormState = () => {
		resetForm({ values: createInitialAcquirerFormValues() })
		setErrors({})
		globalErrorMessage.value = ''
		slugTouchedManually.value = false
		logoPreview.reset('')
	}

	const openForm = () => {
		clearFormState()

		if (isEditMode.value && acquirer.value) {
			resetForm({ values: createAcquirerFormValuesFromAcquirer(acquirer.value) })
			slugTouchedManually.value = true
			logoPreview.reset(acquirer.value.logo || '')
		}
	}

	const closeForm = () => {
		if (submitting.value) {
			return
		}

		clearFormState()
	}

	const updateField = <K extends keyof AcquirerFormValues>(field: K, value: AcquirerFormValues[K]) => {
		setFieldValue(field as never, value as never)

		if (field === 'name' && !slugTouchedManually.value) {
			setFieldValue('slug', slugify(value as string) as never)
		}

		if (field === 'slug') {
			slugTouchedManually.value = true
		}
	}

	const handleLogoChange = (event: Event) => {
		const target = event.target as HTMLInputElement
		const file = target.files?.[0] ?? null

		setFieldValue('logo', file)

		if (!file) {
			logoPreview.reset(acquirer.value?.logo || '')
			return
		}

		logoPreview.setFile(file, acquirer.value?.logo || '')
	}

	const submitForm = handleSubmit(async (formValues) => {
		globalErrorMessage.value = ''

		try {
			if (isEditMode.value) {
				if (!acquirer.value?.uuid) {
					globalErrorMessage.value = 'UUID não informado para edição.'
					return
				}

				await acquirerService.update(acquirer.value.uuid, buildAcquirerPayload(formValues))
			} else {
				await acquirerService.create(buildAcquirerPayload(formValues))
			}

			await onSaved?.()
			clearFormState()
		} catch (error) {
			const normalizedError = normalizeApiError<keyof AcquirerFormValues>(error)

			if (normalizedError.fieldErrors && Object.keys(normalizedError.fieldErrors).length > 0) {
				const nextErrors: Record<string, string> = {}

				for (const [field, messages] of Object.entries(normalizedError.fieldErrors)) {
					if (messages && messages[0]) {
						nextErrors[field] = messages[0]
					}
				}

				setErrors(nextErrors)
			}

			globalErrorMessage.value = normalizedError.message
		}
	})

	watch(isOpen, (open) => {
		if (!open) {
			clearFormState()
			return
		}

		openForm()
	})

	return {
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
	}
}
