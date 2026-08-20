import type { Bank, BankFormValues, CreateBankPayload, UpdateBankPayload } from '../types'

export const createInitialBankFormValues = (): BankFormValues => ({
	name: '',
	code: '',
	status: true,
	logo: null,
})

export const createBankFormValuesFromBank = (bank: Bank): BankFormValues => ({
	name: bank.name ?? '',
	code: bank.code ?? '',
	status: bank.status ?? true,
	logo: null,
})

export const buildBankPayload = (values: BankFormValues): CreateBankPayload | UpdateBankPayload => ({
	name: values.name.trim(),
	code: values.code.trim(),
	status: values.status,
	logo: values.logo ?? null,
})
