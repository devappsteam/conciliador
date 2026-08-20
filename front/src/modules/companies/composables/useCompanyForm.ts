import { computed, ref, watch, type Ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { companyService } from '../services/companyService'
import { fetchAddressByCep } from '../services/cepService'
import { companyFormSchema } from '../schemas/companyForm.schema'
import type { Company, CompanyFormValues } from '../types'
import {
	buildCreateCompanyPayload,
	buildUpdateCompanyPayload,
	createCompanyFormValuesFromCompany,
	createInitialCompanyFormValues,
} from '../utils/company.utils'
import { normalizeApiError } from '@/modules/integrations/utils/normalizeApiError'

export type CompanyFormMode = 'create' | 'edit'

export interface UseCompanyFormOptions {
	isOpen: Ref<boolean>
	mode: Ref<CompanyFormMode>
	company: Ref<Company | null>
	onSaved?: () => void | Promise<void>
}

export const useCompanyForm = ({ isOpen, mode, company, onSaved }: UseCompanyFormOptions) => {
	const globalErrorMessage = ref('')
	const loadingCep = ref(false)

	const validationSchema = computed(() => toTypedSchema(companyFormSchema))

	const { values, errors, handleSubmit, setErrors, setFieldValue, resetForm, isSubmitting } = useForm<CompanyFormValues>({
		validationSchema,
		initialValues: createInitialCompanyFormValues(),
	})

	const fieldErrors = computed(() => errors.value as Partial<Record<keyof CompanyFormValues, string>>)
	const submitting = computed(() => isSubmitting.value)
	const isEditMode = computed(() => mode.value === 'edit')
	const title = computed(() => (isEditMode.value ? 'Editar empresa' : 'Nova empresa'))
	const submitLabel = computed(() => {
		if (submitting.value) {
			return isEditMode.value ? 'A guardar...' : 'A cadastrar...'
		}

		return isEditMode.value ? 'Salvar alterações' : 'Cadastrar empresa'
	})

	const clearFormState = () => {
		resetForm({ values: createInitialCompanyFormValues() })
		setErrors({})
		globalErrorMessage.value = ''
	}

	const openForm = () => {
		clearFormState()

		if (isEditMode.value && company.value) {
			resetForm({ values: createCompanyFormValuesFromCompany(company.value) })
		}
	}

	const closeForm = () => {
		if (submitting.value) {
			return
		}

		clearFormState()
	}

	const updateField = <K extends keyof CompanyFormValues>(field: K, value: CompanyFormValues[K]) => {
		setFieldValue(field as never, value as never)
	}

	const handleCepBlur = async () => {
		const cep = values.zip_code

		if (!cep) {
			return
		}

		loadingCep.value = true

		try {
			const address = await fetchAddressByCep(cep)

			if (address) {
				setFieldValue('street', address.street || values.street)
				setFieldValue('neighborhood', address.neighborhood || values.neighborhood)
				setFieldValue('city', address.city || values.city)
				setFieldValue('state', address.state || values.state)
			}
		} finally {
			loadingCep.value = false
		}
	}

	const submitForm = handleSubmit(async (formValues) => {
		globalErrorMessage.value = ''

		try {
			if (isEditMode.value) {
				if (!company.value?.uuid) {
					globalErrorMessage.value = 'UUID não informado para edição.'
					return
				}

				await companyService.update(company.value.uuid, buildUpdateCompanyPayload(formValues))
			} else {
				await companyService.create(buildCreateCompanyPayload(formValues))
			}

			await onSaved?.()
			clearFormState()
		} catch (error) {
			const normalizedError = normalizeApiError<keyof CompanyFormValues>(error)

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
		loadingCep,
		globalErrorMessage,
		updateField,
		handleCepBlur,
		isEditMode,
		title,
		submitLabel,
		submitForm,
		closeForm,
	}
}
