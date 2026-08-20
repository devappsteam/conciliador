import { computed, ref, watch, type Ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { useFilePreview } from '@/composables/useFilePreview'
import { brandService } from '../services/brandService'
import { brandFormSchema } from '../schemas/brandForm.schema'
import type { Brand, BrandFormValues } from '../types'
import { buildBrandPayload, createBrandFormValuesFromBrand, createInitialBrandFormValues } from '../utils/brand.utils'
import { normalizeApiError } from '../../utils/normalizeApiError'

export type BrandFormMode = 'create' | 'edit'

export interface UseBrandFormOptions {
	isOpen: Ref<boolean>
	mode: Ref<BrandFormMode>
	brand: Ref<Brand | null>
	onSaved?: () => void | Promise<void>
}

export const useBrandForm = ({ isOpen, mode, brand, onSaved }: UseBrandFormOptions) => {
	const globalErrorMessage = ref('')
	const logoPreview = useFilePreview('')

	const validationSchema = computed(() => toTypedSchema(brandFormSchema))

	const { values, errors, handleSubmit, setErrors, setFieldValue, resetForm, isSubmitting } = useForm<BrandFormValues>({
		validationSchema,
		initialValues: createInitialBrandFormValues(),
	})

	const fieldErrors = computed(() => errors.value as Partial<Record<keyof BrandFormValues, string>>)
	const submitting = computed(() => isSubmitting.value)
	const isEditMode = computed(() => mode.value === 'edit')
	const title = computed(() => (isEditMode.value ? 'Editar bandeira' : 'Nova bandeira'))
	const submitLabel = computed(() => {
		if (submitting.value) {
			return isEditMode.value ? 'A guardar...' : 'A cadastrar...'
		}

		return isEditMode.value ? 'Salvar alterações' : 'Cadastrar bandeira'
	})
	const logoPreviewUrl = computed(() => logoPreview.previewUrl.value)

	const clearFormState = () => {
		resetForm({ values: createInitialBrandFormValues() })
		setErrors({})
		globalErrorMessage.value = ''
		logoPreview.reset('')
	}

	const openForm = () => {
		clearFormState()

		if (isEditMode.value && brand.value) {
			resetForm({ values: createBrandFormValuesFromBrand(brand.value) })
			logoPreview.reset(brand.value.logo || '')
		}
	}

	const closeForm = () => {
		if (submitting.value) {
			return
		}

		clearFormState()
	}

	const updateField = <K extends keyof BrandFormValues>(field: K, value: BrandFormValues[K]) => {
		setFieldValue(field as never, value as never)
	}

	const handleLogoChange = (event: Event) => {
		const target = event.target as HTMLInputElement
		const file = target.files?.[0] ?? null

		setFieldValue('logo', file)

		if (!file) {
			logoPreview.reset(brand.value?.logo || '')
			return
		}

		logoPreview.setFile(file, brand.value?.logo || '')
	}

	const submitForm = handleSubmit(async (formValues) => {
		globalErrorMessage.value = ''

		try {
			if (isEditMode.value) {
				if (!brand.value?.uuid) {
					globalErrorMessage.value = 'UUID não informado para edição.'
					return
				}

				await brandService.update(brand.value.uuid, buildBrandPayload(formValues))
			} else {
				await brandService.create(buildBrandPayload(formValues))
			}

			await onSaved?.()
			clearFormState()
		} catch (error) {
			const normalizedError = normalizeApiError<keyof BrandFormValues>(error)

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
