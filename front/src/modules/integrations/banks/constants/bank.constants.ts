export const BANK_LOGO_MAX_SIZE = 1 * 1024 * 1024

export const BANK_LOGO_ACCEPTED_TYPES = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'] as const

export const BANK_FORM_DEFAULT_ERRORS = {
	name: 'Informe o nome do banco.',
	code: 'Informe o código do banco.',
	logoInvalid: 'Selecione um arquivo de imagem válido.',
	logoSize: 'A imagem deve ter no máximo 1MB.',
} as const
