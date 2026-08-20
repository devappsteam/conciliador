import type { Company, CompanyFormValues, CreateCompanyPayload, UpdateCompanyPayload } from '../types'
import { onlyDigits } from './document.utils'

export const createInitialCompanyFormValues = (): CompanyFormValues => ({
	document: '',
	corporate_name: '',
	trade_name: '',
	state_registration: '',
	municipal_registration: '',
	email: '',
	phone: '',
	street: '',
	number: '',
	complement: '',
	neighborhood: '',
	city: '',
	state: '',
	zip_code: '',
	country: 'BR',
	status: 'pending',
})

export const createCompanyFormValuesFromCompany = (company: Company): CompanyFormValues => ({
	document: company.document ?? '',
	corporate_name: company.corporate_name ?? '',
	trade_name: company.trade_name ?? '',
	state_registration: company.state_registration ?? '',
	municipal_registration: company.municipal_registration ?? '',
	email: company.email ?? '',
	phone: company.phone ?? '',
	street: company.street ?? '',
	number: company.number ?? '',
	complement: company.complement ?? '',
	neighborhood: company.neighborhood ?? '',
	city: company.city ?? '',
	state: company.state ?? '',
	zip_code: company.zip_code ?? '',
	country: company.country ?? 'BR',
	status: company.status ?? 'pending',
})

export const buildCreateCompanyPayload = (values: CompanyFormValues): CreateCompanyPayload => ({
	...values,
	document: onlyDigits(values.document),
	zip_code: onlyDigits(values.zip_code),
})

export const buildUpdateCompanyPayload = (values: CompanyFormValues): UpdateCompanyPayload => {
	const { document: _document, ...rest } = buildCreateCompanyPayload(values)
	return rest
}
