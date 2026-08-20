import { z } from 'zod'
import { createImageFileSchema } from '@/schemas/file.schema'
import {
	ACQUIRER_FORM_DEFAULT_ERRORS,
	ACQUIRER_LOGO_ACCEPTED_TYPES,
	ACQUIRER_LOGO_MAX_SIZE,
} from '../constants/acquirer.constants'

const logoSchema = createImageFileSchema({
	acceptedTypes: ACQUIRER_LOGO_ACCEPTED_TYPES,
	maxSizeInBytes: ACQUIRER_LOGO_MAX_SIZE,
	invalidTypeMessage: ACQUIRER_FORM_DEFAULT_ERRORS.logoInvalid,
	maxSizeMessage: ACQUIRER_FORM_DEFAULT_ERRORS.logoSize,
})

const SLUG_REGEX = /^[a-z0-9]+(-[a-z0-9]+)*$/

export const acquirerFormSchema = z.object({
	name: z.string().trim().min(1, ACQUIRER_FORM_DEFAULT_ERRORS.name).max(255),
	slug: z
		.string()
		.trim()
		.min(1, ACQUIRER_FORM_DEFAULT_ERRORS.slug)
		.max(255)
		.regex(SLUG_REGEX, 'Use apenas letras minúsculas, números e hífens (ex: cielo-pay).'),
	code: z.string().trim().min(1, ACQUIRER_FORM_DEFAULT_ERRORS.code).max(255),
	status: z.boolean(),
	logo: z.union([logoSchema, z.null()]).optional(),
})

export type AcquirerFormSchemaValues = z.infer<typeof acquirerFormSchema>
