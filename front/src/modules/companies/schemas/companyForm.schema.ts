import { z } from 'zod'
import { COMPANY_FORM_DEFAULT_ERRORS } from '../constants/company.constants'
import { isValidCnpj, onlyDigits } from '../utils/document.utils'

export const companyFormSchema = z.object({
	document: z
		.string()
		.trim()
		.min(1, COMPANY_FORM_DEFAULT_ERRORS.document)
		.refine((value) => isValidCnpj(value), { message: COMPANY_FORM_DEFAULT_ERRORS.documentInvalid }),
	corporate_name: z.string().trim().min(1, COMPANY_FORM_DEFAULT_ERRORS.corporateName).max(255),
	trade_name: z.string().trim().max(255).optional().or(z.literal('')),
	state_registration: z.string().trim().max(30).optional().or(z.literal('')),
	municipal_registration: z.string().trim().max(30).optional().or(z.literal('')),
	email: z.string().trim().email(COMPANY_FORM_DEFAULT_ERRORS.emailInvalid).optional().or(z.literal('')),
	phone: z.string().trim().max(30).optional().or(z.literal('')),
	street: z.string().trim().min(1, COMPANY_FORM_DEFAULT_ERRORS.street).max(255),
	number: z.string().trim().max(20).optional().or(z.literal('')),
	complement: z.string().trim().max(255).optional().or(z.literal('')),
	neighborhood: z.string().trim().max(255).optional().or(z.literal('')),
	city: z.string().trim().max(255).optional().or(z.literal('')),
	state: z.string().trim().max(2).optional().or(z.literal('')),
	zip_code: z
		.string()
		.trim()
		.optional()
		.or(z.literal(''))
		.refine((value) => !value || onlyDigits(value).length === 8, { message: 'Informe um CEP válido (8 dígitos).' }),
	country: z.string().trim().max(2).optional().or(z.literal('')),
	status: z.enum(['pending', 'active', 'inactive', 'suspended']),
})

export type CompanyFormSchemaValues = z.infer<typeof companyFormSchema>
