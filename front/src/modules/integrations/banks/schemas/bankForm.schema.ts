import { z } from 'zod'
import { createImageFileSchema } from '@/schemas/file.schema'
import { BANK_FORM_DEFAULT_ERRORS, BANK_LOGO_ACCEPTED_TYPES, BANK_LOGO_MAX_SIZE } from '../constants/bank.constants'

const logoSchema = createImageFileSchema({
	acceptedTypes: BANK_LOGO_ACCEPTED_TYPES,
	maxSizeInBytes: BANK_LOGO_MAX_SIZE,
	invalidTypeMessage: BANK_FORM_DEFAULT_ERRORS.logoInvalid,
	maxSizeMessage: BANK_FORM_DEFAULT_ERRORS.logoSize,
})

export const bankFormSchema = z.object({
	name: z.string().trim().min(1, BANK_FORM_DEFAULT_ERRORS.name).max(255),
	code: z.string().trim().min(1, BANK_FORM_DEFAULT_ERRORS.code).max(255),
	status: z.boolean(),
	logo: z.union([logoSchema, z.null()]).optional(),
})

export type BankFormSchemaValues = z.infer<typeof bankFormSchema>
