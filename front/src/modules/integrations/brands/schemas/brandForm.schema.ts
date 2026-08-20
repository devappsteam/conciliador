import { z } from 'zod'
import { createImageFileSchema } from '@/schemas/file.schema'
import { BRAND_FORM_DEFAULT_ERRORS, BRAND_LOGO_ACCEPTED_TYPES, BRAND_LOGO_MAX_SIZE } from '../constants/brand.constants'

const logoSchema = createImageFileSchema({
	acceptedTypes: BRAND_LOGO_ACCEPTED_TYPES,
	maxSizeInBytes: BRAND_LOGO_MAX_SIZE,
	invalidTypeMessage: BRAND_FORM_DEFAULT_ERRORS.logoInvalid,
	maxSizeMessage: BRAND_FORM_DEFAULT_ERRORS.logoSize,
})

export const brandFormSchema = z.object({
	name: z.string().trim().min(1, BRAND_FORM_DEFAULT_ERRORS.name).max(255),
	code: z.string().trim().min(1, BRAND_FORM_DEFAULT_ERRORS.code).max(255),
	status: z.boolean(),
	logo: z.union([logoSchema, z.null()]).optional(),
})

export type BrandFormSchemaValues = z.infer<typeof brandFormSchema>
