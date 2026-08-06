export const USER_ROLES = ['admin', 'bpo', 'comercial', 'suporte'] as const

export type UserRole = (typeof USER_ROLES)[number]

export const USER_ROLE_OPTIONS = [
	{ value: 'admin', label: 'TI / Admin' },
	{ value: 'bpo', label: 'BPO Financeiro' },
	{ value: 'comercial', label: 'Comercial' },
	{ value: 'suporte', label: 'Suporte' },
] as const

export const USER_AVATAR_MAX_SIZE = 2 * 1024 * 1024

export const USER_AVATAR_ACCEPTED_TYPES = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'] as const

export const USER_FORM_DEFAULT_ERRORS = {
	name: 'Informe o nome do usuário.',
	email: 'Informe o e-mail do usuário.',
	role: 'Selecione o perfil do usuário.',
	passwordCreate: 'A senha é obrigatória no cadastro.',
	passwordMin: 'A senha deve ter no mínimo 8 caracteres.',
	passwordConfirmation: 'A confirmação de senha não confere.',
	avatarInvalid: 'Selecione um arquivo de imagem válido.',
	avatarSize: 'A imagem deve ter no máximo 2MB.',
} as const
