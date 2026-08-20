export const COMPANY_STATUS_OPTIONS = [
	{ value: 'pending', label: 'Pendente' },
	{ value: 'active', label: 'Ativa' },
	{ value: 'inactive', label: 'Inativa' },
	{ value: 'suspended', label: 'Suspensa' },
] as const

export const COMPANY_STATUS_LABELS: Record<string, string> = {
	pending: 'Pendente',
	active: 'Ativa',
	inactive: 'Inativa',
	suspended: 'Suspensa',
}

export const COMPANY_STATUS_BADGE_CLASSES: Record<string, string> = {
	pending: 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400',
	active: 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400',
	inactive: 'bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
	suspended: 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400',
}

export const COMPANY_FORM_DEFAULT_ERRORS = {
	document: 'Informe o CNPJ.',
	documentInvalid: 'Informe um CNPJ válido.',
	corporateName: 'Informe a razão social.',
	street: 'Informe o logradouro.',
	emailInvalid: 'Informe um e-mail válido.',
} as const
