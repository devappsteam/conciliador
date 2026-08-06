import { z } from 'zod'

export interface EmailSchemaMessages {
	required: string
	invalid: string
}

export const createEmailSchema = (messages: EmailSchemaMessages) =>
	z.string().trim().min(1, messages.required).email(messages.invalid)

export const emailSchema = createEmailSchema({
	required: 'Informe o e-mail.',
	invalid: 'Informe um e-mail válido.',
})
