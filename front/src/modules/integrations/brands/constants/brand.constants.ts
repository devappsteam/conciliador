export const BRAND_LOGO_MAX_SIZE = 1 * 1024 * 1024

export const BRAND_LOGO_ACCEPTED_TYPES = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'] as const

export const BRAND_FORM_DEFAULT_ERRORS = {
	name: 'Informe o nome da bandeira.',
	code: 'Informe o código da bandeira.',
	logoInvalid: 'Selecione um arquivo de imagem válido.',
	logoSize: 'A imagem deve ter no máximo 1MB.',
} as const
