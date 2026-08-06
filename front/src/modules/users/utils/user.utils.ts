import axios from 'axios'
import { ZodError } from 'zod'
import type { ValidationErrorResponse } from '@/types/api'
import { USER_AVATAR_ACCEPTED_TYPES, USER_AVATAR_MAX_SIZE, USER_FORM_DEFAULT_ERRORS } from '../constants/user.constants'
import type { User, UserFormValues, CreateUserPayload, UpdateUserPayload } from '../types'

export interface NormalizedApiError {
	message: string
	fieldErrors: Partial<Record<keyof UserFormValues, string[]>>
	status?: number
}

export const createInitialUserFormValues = (): UserFormValues => ({
	name: '',
	email: '',
	role: 'admin',
	password: '',
	password_confirmation: '',
	avatar: null,
})

export const createUserFormValuesFromUser = (user: User): UserFormValues => ({
	name: user.name ?? '',
	email: user.email ?? '',
	role: user.role ?? 'admin',
	password: '',
	password_confirmation: '',
	avatar: null,
})

export const buildCreateUserPayload = (values: UserFormValues): CreateUserPayload => ({
	name: values.name.trim(),
	email: values.email.trim(),
	role: (values.role || 'admin') as CreateUserPayload['role'],
	password: values.password.trim(),
	password_confirmation: values.password_confirmation.trim(),
	avatar: values.avatar ?? null,
})

export const buildUpdateUserPayload = (values: UserFormValues): UpdateUserPayload => ({
	name: values.name.trim(),
	email: values.email.trim(),
	role: (values.role || 'admin') as UpdateUserPayload['role'],
	password: values.password?.trim() || undefined,
	password_confirmation: values.password_confirmation?.trim() || undefined,
	avatar: values.avatar ?? null,
})

export const validateUserAvatarFile = (file: File | null): string | null => {
	if (!file) {
		return null
	}

	if (!USER_AVATAR_ACCEPTED_TYPES.includes(file.type as (typeof USER_AVATAR_ACCEPTED_TYPES)[number])) {
		return USER_FORM_DEFAULT_ERRORS.avatarInvalid
	}

	if (file.size > USER_AVATAR_MAX_SIZE) {
		return USER_FORM_DEFAULT_ERRORS.avatarSize
	}

	return null
}

export const normalizeValidationErrors = (
	error: ValidationErrorResponse,
): NormalizedApiError => ({
	message: error.message || 'Existem campos inválidos no formulário.',
	fieldErrors: error.errors as NormalizedApiError['fieldErrors'],
})

export const normalizeUserApiError = (error: unknown): NormalizedApiError => {
	if (axios.isAxiosError(error)) {
		const status = error.response?.status
		const data = error.response?.data as ValidationErrorResponse | { message?: string } | undefined

		if (status === 422 && data && 'errors' in data) {
			return {
				message: data.message || 'Existem campos inválidos no formulário.',
				fieldErrors: data.errors as NormalizedApiError['fieldErrors'],
				status,
			}
		}

		const fallbackMessages: Record<number, string> = {
			400: 'Não foi possível processar o pedido do usuário.',
			401: 'A sessão expirou ou não está autenticado.',
			403: 'Não tem permissão para executar esta ação.',
			404: 'O usuário solicitado não foi encontrado.',
			500: 'Ocorreu um erro interno no servidor.',
		}

		return {
			message:
				data?.message ||
				(status ? fallbackMessages[status] : undefined) ||
				(error.message === 'Network Error'
					? 'Não foi possível estabelecer ligação com o servidor.'
					: 'Ocorreu um erro inesperado ao processar o usuário.'),
			fieldErrors: {},
			status,
		}
	}

	if (error instanceof ZodError) {
		const fieldErrors = error.flatten().fieldErrors as Partial<Record<keyof UserFormValues, string[]>>
		return {
			message: 'Existem campos inválidos no formulário.',
			fieldErrors: {
				name: fieldErrors.name,
				email: fieldErrors.email,
				role: fieldErrors.role,
				password: fieldErrors.password,
				password_confirmation: fieldErrors.password_confirmation,
				avatar: fieldErrors.avatar,
			},
		}
	}

	return {
		message: 'Ocorreu um erro inesperado ao processar o usuário.',
		fieldErrors: {},
	}
}
