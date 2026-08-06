import { computed, ref, watch, type Ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { useFilePreview } from '@/composables/useFilePreview'
import { userService } from '../services/userService'
import { createUserFormSchema, updateUserFormSchema } from '../schemas/userForm.schema'
import type { UserFormValues, User } from '../types'
import {
	buildCreateUserPayload,
	buildUpdateUserPayload,
	createInitialUserFormValues,
	createUserFormValuesFromUser,
	normalizeUserApiError,
	validateUserAvatarFile,
} from '../utils/user.utils'

export type UserFormMode = 'create' | 'edit'

export interface UseUserFormOptions {
	isOpen: Ref<boolean>
	mode: Ref<UserFormMode>
	userUuid: Ref<string | null | undefined>
	onSaved?: () => void | Promise<void>
}

export const useUserForm = ({ isOpen, mode, userUuid, onSaved }: UseUserFormOptions) => {
	const loadingUserDetails = ref(false)
	const globalErrorMessage = ref('')
	const loadedUserUuid = ref<string | null>(null)
	const loadedAvatarUrl = ref('')
	const requestSequence = ref(0)
	const avatarPreview = useFilePreview('')

	const validationSchema = computed(() =>
		mode.value === 'create' ? toTypedSchema(createUserFormSchema) : toTypedSchema(updateUserFormSchema),
	)

	const { values, errors, handleSubmit, setErrors, setFieldValue, resetForm, isSubmitting } = useForm<UserFormValues>({
		validationSchema,
		initialValues: createInitialUserFormValues(),
	})

	const fieldErrors = computed(() => errors.value as Partial<Record<keyof UserFormValues, string>>)
	const submitting = computed(() => isSubmitting.value)

	const isEditMode = computed(() => mode.value === 'edit')
	const title = computed(() => (isEditMode.value ? 'Editar utilizador' : 'Novo utilizador'))
	const submitLabel = computed(() => {
		if (submitting.value) {
			return isEditMode.value ? 'A guardar...' : 'A cadastrar...'
		}

		return isEditMode.value ? 'Salvar alterações' : 'Cadastrar utilizador'
	})
	const avatarPreviewUrl = computed(() => avatarPreview.previewUrl.value)

	const clearFormState = () => {
		resetForm({ values: createInitialUserFormValues() })
		setErrors({})
		globalErrorMessage.value = ''
		loadedUserUuid.value = null
		loadedAvatarUrl.value = ''
		avatarPreview.reset('')
	}

	const syncUserIntoForm = (user: User) => {
		resetForm({ values: createUserFormValuesFromUser(user) })
		loadedAvatarUrl.value = user.avatar_url || ''
		avatarPreview.reset(loadedAvatarUrl.value)
		loadedUserUuid.value = user.uuid
	}

	const fetchUserDetails = async (uuid: string) => {
		const requestId = ++requestSequence.value
		loadingUserDetails.value = true
		globalErrorMessage.value = ''
		setErrors({})

		try {
			const response = await userService.getByUuid(uuid)

			if (requestId !== requestSequence.value) {
				return
			}

			syncUserIntoForm(response.data)
		} catch (error) {
			if (requestId !== requestSequence.value) {
				return
			}

			const normalizedError = normalizeUserApiError(error)
			globalErrorMessage.value =
				normalizedError.message || 'Não foi possível carregar os dados do utilizador.'
		} finally {
			if (requestId === requestSequence.value) {
				loadingUserDetails.value = false
			}
		}
	}

	const openForm = async () => {
		clearFormState()

		if (!isEditMode.value) {
			return
		}

		if (!userUuid.value) {
			globalErrorMessage.value = 'Identificador do utilizador não informado para edição.'
			return
		}

		await fetchUserDetails(userUuid.value)
	}

	const closeForm = () => {
		if (loadingUserDetails.value || submitting.value) {
			return
		}

		clearFormState()
	}

	const handleAvatarChange = (event: Event) => {
		const target = event.target as HTMLInputElement
		const file = target.files?.[0] ?? null

		if (!file) {
			setFieldValue('avatar', null)
			avatarPreview.reset(loadedAvatarUrl.value)
			return
		}

		const validationMessage = validateUserAvatarFile(file)

		if (validationMessage) {
			setErrors({ avatar: validationMessage })
			target.value = ''
			setFieldValue('avatar', null)
			avatarPreview.reset(loadedAvatarUrl.value)
			return
		}

		setErrors({ avatar: '' })
		avatarPreview.setFile(file, loadedAvatarUrl.value)
		setFieldValue('avatar', file)
	}

	const submitForm = handleSubmit(async (formValues) => {
		globalErrorMessage.value = ''

		try {
			if (isEditMode.value) {
				if (!userUuid.value) {
					globalErrorMessage.value = 'UUID não informado para edição.'
					return
				}

				await userService.update(userUuid.value, buildUpdateUserPayload(formValues))
			} else {
				await userService.create(buildCreateUserPayload(formValues))
			}

			await onSaved?.()
			clearFormState()
		} catch (error) {
			const normalizedError = normalizeUserApiError(error)

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

	watch(isOpen, async (open) => {
		requestSequence.value += 1

		if (!open) {
			closeForm()
			return
		}

		await openForm()
	})

	watch(userUuid, async (uuid, previousUuid) => {
		if (!isOpen.value || !isEditMode.value || !uuid || uuid === previousUuid || uuid === loadedUserUuid.value) {
			return
		}

		await fetchUserDetails(uuid)
	})

	return {
		values,
		errors: fieldErrors,
		isSubmitting: submitting,
		loadingUserDetails,
		globalErrorMessage,
		avatarPreviewUrl,
		isEditMode,
		title,
		submitLabel,
		handleAvatarChange,
		submitForm,
		closeForm,
	}
}
