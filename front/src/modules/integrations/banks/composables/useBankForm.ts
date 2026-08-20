import { computed, ref, watch, type Ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { useFilePreview } from '@/composables/useFilePreview'
import { bankService } from '../services/bankService'
import { bankFormSchema } from '../schemas/bankForm.schema'
import type { Bank, BankFormValues } from '../types'
import { buildBankPayload, createBankFormValuesFromBank, createInitialBankFormValues } from '../utils/bank.utils'
import { normalizeApiError } from '../../utils/normalizeApiError'

export type BankFormMode = 'create' | 'edit'

export interface UseBankFormOptions {
	isOpen: Ref<boolean>
	mode: Ref<BankFormMode>
	bank: Ref<Bank | null>
	onSaved?: () => void | Promise<void>
}

export const useBankForm = ({ isOpen, mode, bank, onSaved }: UseBankFormOptions) => {
	const globalErrorMessage = ref('')
	const logoPreview = useFilePreview('')

	const validationSchema = computed(() => toTypedSchema(bankFormSchema))

	const { values, errors, handleSubmit, setErrors, setFieldValue, resetForm, isSubmitting } = useForm<BankFormValues>({
		validationSchema,
		initialValues: createInitialBankFormValues(),
	})

	const fieldErrors = computed(() => errors.value as Partial<Record<keyof BankFormValues, string>>)
	const submitting = computed(() => isSubmitting.value)
	const isEditMode = computed(() => mode.value === 'edit')
	const title = computed(() => (isEditMode.value ? 'Editar banco' : 'Novo banco'))
	const submitLabel = computed(() => {
		if (submitting.value) {
			return isEditMode.value ? 'A guardar...' : 'A cadastrar...'
		}

		return isEditMode.value ? 'Salvar alterações' : 'Cadastrar banco'
	})
	const logoPreviewUrl = computed(() => logoPreview.previewUrl.value)

	const clearFormState = () => {
		resetForm({ values: createInitialBankFormValues() })
		setErrors({})
		globalErrorMessage.value = ''
		logoPreview.reset('')
	}

	const openForm = () => {
		clearFormState()

		if (isEditMode.value && bank.value) {
			resetForm({ values: createBankFormValuesFromBank(bank.value) })
			logoPreview.reset(bank.value.logo || '')
		}
	}

	const closeForm = () => {
		if (submitting.value) {
			return
		}

		clearFormState()
	}

	const updateField = <K extends keyof BankFormValues>(field: K, value: BankFormValues[K]) => {
		setFieldValue(field as never, value as never)
	}

	const handleLogoChange = (event: Event) => {
		const target = event.target as HTMLInputElement
		const file = target.files?.[0] ?? null

		setFieldValue('logo', file)

		if (!file) {
			logoPreview.reset(bank.value?.logo || '')
			return
		}

		logoPreview.setFile(file, bank.value?.logo || '')
	}

	const submitForm = handleSubmit(async (formValues) => {
		globalErrorMessage.value = ''

		try {
			if (isEditMode.value) {
				if (!bank.value?.uuid) {
					globalErrorMessage.value = 'UUID não informado para edição.'
					return
				}

				await bankService.update(bank.value.uuid, buildBankPayload(formValues))
			} else {
				await bankService.create(buildBankPayload(formValues))
			}

			await onSaved?.()
			clearFormState()
		} catch (error) {
			const normalizedError = normalizeApiError<keyof BankFormValues>(error)

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
