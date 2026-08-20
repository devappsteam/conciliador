import axios from 'axios'
import { ZodError } from 'zod'
import type { ValidationErrorResponse } from '@/types/api'

export interface NormalizedApiError<TFields extends string = string> {
	message: string
	fieldErrors: Partial<Record<TFields, string[]>>
	status?: number
}

const FALLBACK_MESSAGES: Record<number, string> = {
	400: 'Não foi possível processar o pedido.',
	401: 'A sessão expirou ou não está autenticado.',
	403: 'Não tem permissão para executar esta ação.',
	404: 'O registro solicitado não foi encontrado.',
	500: 'Ocorreu um erro interno no servidor.',
}

// Normaliza erros de Axios (422 com erros por campo) e ZodError num formato único para os forms
export const normalizeApiError = <TFields extends string = string>(error: unknown): NormalizedApiError<TFields> => {
	if (axios.isAxiosError(error)) {
		const status = error.response?.status
		const data = error.response?.data as ValidationErrorResponse | { message?: string } | undefined

		if (status === 422 && data && 'errors' in data) {
			return {
				message: data.message || 'Existem campos inválidos no formulário.',
				fieldErrors: data.errors as NormalizedApiError<TFields>['fieldErrors'],
				status,
			}
		}

		return {
			message:
				data?.message ||
				(status ? FALLBACK_MESSAGES[status] : undefined) ||
				(error.message === 'Network Error'
					? 'Não foi possível estabelecer ligação com o servidor.'
					: 'Ocorreu um erro inesperado ao processar o pedido.'),
			fieldErrors: {},
			status,
		}
	}

	if (error instanceof ZodError) {
		return {
			message: 'Existem campos inválidos no formulário.',
			fieldErrors: error.flatten().fieldErrors as NormalizedApiError<TFields>['fieldErrors'],
		}
	}

	return {
		message: 'Ocorreu um erro inesperado ao processar o pedido.',
		fieldErrors: {},
	}
}
