<script setup lang="ts">
import axios from 'axios'
import { computed, onUnmounted, reactive, ref, watch } from 'vue'
import AppButton from '@/components/AppButton.vue'
import type { ValidationErrorResponse } from '@/types/api'
import { userService } from '../services/userService'
import type { User } from '../types'

type FormMode = 'create' | 'edit'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		mode: FormMode
		userUuid?: string | null
	}>(),
	{
		userUuid: null,
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
	(event: 'saved'): void
}>()

const loadingUserDetails = ref(false)
const submitting = ref(false)
const globalErrorMessage = ref('')
const fieldErrors = ref<Record<string, string[]>>({})
const avatarPreviewUrl = ref('')
const loadedUserUuid = ref<string | null>(null)
let objectPreviewUrl: string | null = null

const form = reactive({
	name: '',
	email: '',
	role: '',
	password: undefined as string | undefined,
	password_confirmation: undefined as string | undefined,
	avatar: null as File | null,
})

const title = computed(() =>
	props.mode === 'create' ? 'Novo utilizador' : 'Editar utilizador',
)

const submitLabel = computed(() => {
	if (submitting.value) {
		return props.mode === 'create' ? 'A cadastrar...' : 'A guardar...'
	}

	return props.mode === 'create' ? 'Cadastrar utilizador' : 'Salvar alterações'
})

const isEditMode = computed(() => props.mode === 'edit')

const clearObjectPreview = () => {
	if (objectPreviewUrl) {
		URL.revokeObjectURL(objectPreviewUrl)
		objectPreviewUrl = null
	}
}

const resetForm = () => {
	form.name = ''
	form.email = ''
	form.role = ''
	form.password = undefined
	form.password_confirmation = undefined
	form.avatar = null
	avatarPreviewUrl.value = ''
	loadedUserUuid.value = null

	clearObjectPreview()
	fieldErrors.value = {}
	globalErrorMessage.value = ''
}

const close = () => {
	if (submitting.value || loadingUserDetails.value) {
		return
	}

	emit('update:modelValue', false)
}

const setFieldError = (field: string, message: string) => {
	fieldErrors.value = {
		...fieldErrors.value,
		[field]: [message],
	}
}

const clearFieldError = (field: string) => {
	const nextErrors = { ...fieldErrors.value }
	delete nextErrors[field]
	fieldErrors.value = nextErrors
}

const getFieldError = (field: string) => fieldErrors.value[field]?.[0]

const validateForm = () => {
	fieldErrors.value = {}
	globalErrorMessage.value = ''

	const name = form.name.trim()
	const email = form.email.trim()

	if (!name) {
		setFieldError('name', 'Informe o nome do utilizador.')
	} else if (name.length < 2) {
		setFieldError('name', 'O nome deve ter pelo menos 2 caracteres.')
	}

	if (!email) {
		setFieldError('email', 'Informe o e-mail do utilizador.')
	} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
		setFieldError('email', 'Informe um e-mail válido.')
	}

	if (!form.role) {
		setFieldError('role', 'Selecione o perfil do utilizador.')
	}

	if (props.mode === 'create' && !form.password) {
		setFieldError('password', 'Senha é obrigatória no cadastro.')
	}

	if (form.password && form.password.length < 8) {
		setFieldError('password', 'A senha deve ter no mínimo 8 caracteres.')
	}

	if (form.password || form.password_confirmation) {
		if (form.password !== form.password_confirmation) {
			setFieldError('password_confirmation', 'A confirmação de senha não confere.')
		}
	}

	return Object.keys(fieldErrors.value).length === 0
}

const fillFormForEdit = (user: User) => {
	form.name = user.name || ''
	form.email = user.email || ''
	form.role = user.role || ''
	form.password = undefined
	form.password_confirmation = undefined
	form.avatar = null
	avatarPreviewUrl.value = user.avatar_url || ''
}

const fetchUserDetails = async (uuid: string) => {
	loadingUserDetails.value = true
	globalErrorMessage.value = ''
	fieldErrors.value = {}

	try {
		const response = await userService.getByUuid(uuid)
		fillFormForEdit(response.data)
		loadedUserUuid.value = uuid
	} catch (error) {
		if (axios.isAxiosError(error)) {
			globalErrorMessage.value =
				error.response?.data?.message || 'Não foi possível carregar os dados do utilizador.'
		} else {
			globalErrorMessage.value = 'Erro inesperado ao carregar os dados do utilizador.'
		}
	} finally {
		loadingUserDetails.value = false
	}
}

const openState = async () => {
	resetForm()

	if (isEditMode.value) {
		if (!props.userUuid) {
			globalErrorMessage.value = 'Identificador do utilizador não informado para edição.'
			return
		}

		await fetchUserDetails(props.userUuid)
	}
}

const handleAvatarChange = (event: Event) => {
	const target = event.target as HTMLInputElement
	const file = target.files?.[0]

	if (!file) {
		return
	}

	clearFieldError('avatar')

	if (!file.type.startsWith('image/')) {
		setFieldError('avatar', 'Selecione um arquivo de imagem válido.')
		target.value = ''
		return
	}

	const maxSizeInBytes = 2 * 1024 * 1024

	if (file.size > maxSizeInBytes) {
		setFieldError('avatar', 'A imagem deve ter no máximo 2MB.')
		target.value = ''
		return
	}

	clearObjectPreview()
	objectPreviewUrl = URL.createObjectURL(file)

	form.avatar = file
	avatarPreviewUrl.value = objectPreviewUrl
}

const buildPayload = () => {
	const payload = {
		name: form.name.trim(),
		email: form.email.trim(),
		role: form.role,
		password: form.password?.trim() || undefined,
		password_confirmation: form.password_confirmation?.trim() || undefined,
		avatar: form.avatar,
	}

	if (props.mode === 'edit' && !payload.password) {
		payload.password_confirmation = undefined
	}

	return payload
}

const handleSubmit = async () => {
	if (loadingUserDetails.value || submitting.value) {
		return
	}

	if (!validateForm()) {
		return
	}

	submitting.value = true
	globalErrorMessage.value = ''

	try {
		const payload = buildPayload()

		if (props.mode === 'create') {
			await userService.create(payload)
		} else {
			if (!props.userUuid) {
				throw new Error('UUID não informado para edição.')
			}

			await userService.update(props.userUuid, payload)
		}

		emit('saved')
		emit('update:modelValue', false)
	} catch (error) {
		if (axios.isAxiosError(error)) {
			const response = error.response

			if (response?.status === 422) {
				const validationData = response.data as ValidationErrorResponse
				fieldErrors.value = validationData.errors || {}
				globalErrorMessage.value = validationData.message || 'Existem campos inválidos no formulário.'
			} else {
				globalErrorMessage.value = response?.data?.message || 'Não foi possível salvar o utilizador.'
			}
		} else {
			globalErrorMessage.value = 'Ocorreu um erro inesperado ao salvar o utilizador.'
		}
	} finally {
		submitting.value = false
	}
}

watch(
	() => props.modelValue,
	async (isOpen) => {
		if (isOpen) {
			await openState()
			return
		}

		resetForm()
	},
)

watch(
	() => props.userUuid,
	async (uuid, previousUuid) => {
		if (
			!props.modelValue ||
			!isEditMode.value ||
			!uuid ||
			uuid === previousUuid ||
			uuid === loadedUserUuid.value
		) {
			return
		}

		await fetchUserDetails(uuid)
	},
)

onUnmounted(() => {
	clearObjectPreview()
})
</script>
<template>
	<Teleport to="body">
		<div
			v-if="modelValue"
			class="fixed inset-0 z-50 flex items-center justify-center p-4"
			role="dialog"
			aria-modal="true"
			aria-labelledby="user-form-title"
		>
			<div class="absolute inset-0 bg-gray-900/60" @click="close" />

			<div
				class="relative w-full max-w-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl"
			>
				<div class="p-6 border-b border-gray-100 dark:border-gray-700">
					<h2 id="user-form-title" class="text-lg font-semibold text-gray-900 dark:text-white">
						{{ title }}
					</h2>
					<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
						{{
							isEditMode
								? 'Atualize os dados do utilizador e salve as alterações.'
								: 'Preencha os dados para cadastrar um novo utilizador.'
						}}
					</p>
				</div>

				<div v-if="loadingUserDetails" class="p-6 space-y-4 animate-pulse">
					<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-44"></div>
					<div class="h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
					<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-44"></div>
					<div class="h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div>
							<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 mb-2"></div>
							<div class="h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
						</div>
						<div>
							<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 mb-2"></div>
							<div class="h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
						</div>
					</div>
				</div>

				<form v-else @submit.prevent="handleSubmit" class="p-6 space-y-4" novalidate>
					<div v-if="globalErrorMessage" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
						{{ globalErrorMessage }}
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div class="md:col-span-2">
							<label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Nome
							</label>
							<input
								id="user-name"
								v-model="form.name"
								type="text"
								:disabled="submitting"
								class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
								:class="getFieldError('name') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
								placeholder="Nome completo"
							/>
							<p v-if="getFieldError('name')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('name') }}
							</p>
						</div>

						<div>
							<label for="user-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								E-mail
							</label>
							<input
								id="user-email"
								v-model="form.email"
								type="email"
								autocomplete="email"
								:disabled="submitting"
								class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
								:class="getFieldError('email') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
								placeholder="utilizador@empresa.com"
							/>
							<p v-if="getFieldError('email')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('email') }}
							</p>
						</div>

						<div>
							<label for="user-role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Perfil
							</label>
							<select
								id="user-role"
								v-model="form.role"
								:disabled="submitting"
								class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
								:class="getFieldError('role') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
							>
								<option value="">Selecione</option>
								<option value="admin">TI / Admin</option>
								<option value="bpo">BPO Financeiro</option>
								<option value="comercial">Comercial</option>
								<option value="suporte">Suporte</option>
							</select>
							<p v-if="getFieldError('role')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('role') }}
							</p>
						</div>

						<div>
							<label for="user-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								{{ isEditMode ? 'Nova senha (opcional)' : 'Senha' }}
							</label>
							<input
								id="user-password"
								v-model="form.password"
								type="password"
								autocomplete="new-password"
								:disabled="submitting"
								class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
								:class="getFieldError('password') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
								placeholder="••••••••"
							/>
							<p v-if="getFieldError('password')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('password') }}
							</p>
						</div>

						<div>
							<label for="user-password-confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Confirmar senha
							</label>
							<input
								id="user-password-confirm"
								v-model="form.password_confirmation"
								type="password"
								autocomplete="new-password"
								:disabled="submitting"
								class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
								:class="getFieldError('password_confirmation') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
								placeholder="••••••••"
							/>
							<p v-if="getFieldError('password_confirmation')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('password_confirmation') }}
							</p>
						</div>

						<div class="md:col-span-2">
							<label for="user-avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Avatar
							</label>

							<div class="flex items-center gap-4">
								<div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden flex items-center justify-center text-xs text-gray-500">
									<img v-if="avatarPreviewUrl" :src="avatarPreviewUrl" alt="Pré-visualização do avatar" class="w-full h-full object-cover" />
									<span v-else>Sem foto</span>
								</div>

								<input
									id="user-avatar"
									type="file"
									accept="image/*"
									:disabled="submitting"
									class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-3 file:px-3 file:py-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300"
									@change="handleAvatarChange"
								/>
							</div>

							<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
								Formatos aceitos: imagem. Tamanho máximo: 2MB.
							</p>
							<p v-if="getFieldError('avatar')" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
								{{ getFieldError('avatar') }}
							</p>
						</div>
					</div>
				</form>

				<div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
					<AppButton variant="outline" :disabled="submitting || loadingUserDetails" @click="close">
						Cancelar
					</AppButton>
					<AppButton :disabled="submitting || loadingUserDetails" @click="handleSubmit">
						{{ submitLabel }}
					</AppButton>
				</div>
			</div>
		</div>
	</Teleport>
</template>
