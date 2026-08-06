import { z } from 'zod'
import { USER_AVATAR_ACCEPTED_TYPES, USER_AVATAR_MAX_SIZE, USER_FORM_DEFAULT_ERRORS, USER_ROLES } from '../constants/user.constants'
import { createEmailSchema } from '@/schemas/email.schema'
import { createImageFileSchema } from '@/schemas/file.schema'
import { createPasswordSchema } from '@/schemas/password.schema'

const avatarSchema = createImageFileSchema({
	acceptedTypes: USER_AVATAR_ACCEPTED_TYPES,
	maxSizeInBytes: USER_AVATAR_MAX_SIZE,
	invalidTypeMessage: USER_FORM_DEFAULT_ERRORS.avatarInvalid,
	maxSizeMessage: USER_FORM_DEFAULT_ERRORS.avatarSize,
})

const baseUserFormSchema = z.object({
	name: z
		.string()
		.trim()
		.min(1, USER_FORM_DEFAULT_ERRORS.name)
		.min(2, 'O nome deve ter pelo menos 2 caracteres.'),
	email: createEmailSchema({
		required: USER_FORM_DEFAULT_ERRORS.email,
		invalid: 'Informe um e-mail válido.',
	}),
	role: z
		.string()
		.trim()
		.min(1, USER_FORM_DEFAULT_ERRORS.role)
		.refine((value) => USER_ROLES.includes(value as (typeof USER_ROLES)[number]), {
			message: 'Selecione um perfil de utilizador válido.',
		}),
	password: z.string().trim().optional(),
	password_confirmation: z.string().trim().optional(),
	avatar: z.union([avatarSchema, z.null()]).optional(),
})

export const createUserFormSchema = baseUserFormSchema.extend({
	password: createPasswordSchema(
		{
			required: USER_FORM_DEFAULT_ERRORS.passwordCreate,
			minLength: USER_FORM_DEFAULT_ERRORS.passwordMin,
		},
		8,
	),
	password_confirmation: createPasswordSchema(
		{
			required: USER_FORM_DEFAULT_ERRORS.passwordCreate,
			minLength: USER_FORM_DEFAULT_ERRORS.passwordMin,
		},
		8,
	),
}).refine((values) => values.password === values.password_confirmation, {
	message: USER_FORM_DEFAULT_ERRORS.passwordConfirmation,
	path: ['password_confirmation'],
})

export const updateUserFormSchema = baseUserFormSchema.extend({
	password: z.string().trim().min(8, USER_FORM_DEFAULT_ERRORS.passwordMin).optional().or(z.literal('')),
	password_confirmation: z.string().trim().min(8, USER_FORM_DEFAULT_ERRORS.passwordMin).optional().or(z.literal('')),
}).refine((values) => {
	if (!values.password && !values.password_confirmation) {
		return true
	}

	return values.password === values.password_confirmation
}, {
	message: USER_FORM_DEFAULT_ERRORS.passwordConfirmation,
	path: ['password_confirmation'],
})

export const userFormSchema = z.union([createUserFormSchema, updateUserFormSchema])
