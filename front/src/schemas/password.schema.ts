import { z } from 'zod'

export interface PasswordSchemaMessages {
	required: string
	minLength: string
}

export const createPasswordSchema = (messages: PasswordSchemaMessages, minLength = 8) =>
	z.string().trim().min(1, messages.required).min(minLength, messages.minLength)

export const passwordSchema = createPasswordSchema({
	required: 'Informe a senha.',
	minLength: 'A senha deve ter no mínimo 8 caracteres.',
})
