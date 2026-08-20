import type { Acquirer, AcquirerFormValues, CreateAcquirerPayload, UpdateAcquirerPayload } from '../types'

export const slugify = (value: string): string =>
	value
		.normalize('NFD')
		.replace(/[\u0300-\u036f]/g, '')
		.toLowerCase()
		.trim()
		.replace(/[^a-z0-9\s-]/g, '')
		.replace(/\s+/g, '-')
		.replace(/-+/g, '-')
		.replace(/^-|-$/g, '')

export const createInitialAcquirerFormValues = (): AcquirerFormValues => ({
	name: '',
	slug: '',
	code: '',
	status: true,
	logo: null,
})

export const createAcquirerFormValuesFromAcquirer = (acquirer: Acquirer): AcquirerFormValues => ({
	name: acquirer.name ?? '',
	slug: acquirer.slug ?? '',
	code: acquirer.code ?? '',
	status: acquirer.status ?? true,
	logo: null,
})

export const buildAcquirerPayload = (values: AcquirerFormValues): CreateAcquirerPayload | UpdateAcquirerPayload => ({
	name: values.name.trim(),
	slug: values.slug.trim(),
	code: values.code.trim(),
	status: values.status,
	logo: values.logo ?? null,
})

export const validateAcquirerLogoFile = (
	file: File | null,
	acceptedTypes: readonly string[],
	maxSize: number,
	messages: { invalid: string; size: string },
): string | null => {
	if (!file) {
		return null
	}

	if (!acceptedTypes.includes(file.type)) {
		return messages.invalid
	}

	if (file.size > maxSize) {
		return messages.size
	}

	return null
}
