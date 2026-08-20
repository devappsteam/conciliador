<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import AppButton from '@/components/AppButton.vue'
import type { Acquirer } from '../types'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		acquirer: Acquirer | null
		loading?: boolean
		errorMessage?: string | null
	}>(),
	{
		loading: false,
		errorMessage: null,
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
	(event: 'confirm'): void
}>()

const confirmationInput = ref('')
const expectedKeyword = 'confirmar'

const isConfirmationValid = computed(() => {
	if (!props.acquirer) {
		return false
	}

	return confirmationInput.value.trim().toLowerCase() === expectedKeyword
})

const close = () => {
	if (props.loading) {
		return
	}

	emit('update:modelValue', false)
}

const confirm = () => {
	if (!isConfirmationValid.value || props.loading) {
		return
	}

	emit('confirm')
}

watch(
	() => props.modelValue,
	(isOpen) => {
		if (isOpen) {
			confirmationInput.value = ''
		}
	},
)
</script>

<template>
	<Teleport to="body">
		<div
			v-if="modelValue"
			class="fixed inset-0 z-50 flex items-center justify-center p-4"
			role="dialog"
			aria-modal="true"
			aria-labelledby="delete-acquirer-title"
		>
			<div class="absolute inset-0 bg-gray-900/60" @click="close" />

			<div class="relative w-full max-w-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl">
				<div class="p-6 border-b border-gray-100 dark:border-gray-700">
					<h2 id="delete-acquirer-title" class="text-lg font-semibold text-gray-900 dark:text-white">
						Confirmar exclusão de adquirente
					</h2>
					<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
						Esta ação é irreversível. A adquirente
						<strong class="text-gray-700 dark:text-gray-200">{{ acquirer?.name }}</strong>
						será removida permanentemente.
					</p>
				</div>

				<div class="p-6 space-y-4">
					<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm">
						Para continuar, digite <span class="font-semibold">CONFIRMAR</span>.
					</div>

					<div>
						<label for="delete-acquirer-confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Confirmação de segurança
						</label>
						<input
							id="delete-acquirer-confirmation"
							v-model="confirmationInput"
							type="text"
							autocomplete="off"
							autocapitalize="off"
							autocorrect="off"
							spellcheck="false"
							:disabled="loading"
							class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg outline-none transition-colors focus:border-red-500 focus:ring-1 focus:ring-red-500"
							placeholder="Digite CONFIRMAR para prosseguir..."
						/>
					</div>

					<div v-if="errorMessage" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
						{{ errorMessage }}
					</div>
				</div>

				<div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
					<AppButton variant="outline" :disabled="loading" @click="close">Cancelar</AppButton>
					<AppButton variant="danger" :disabled="!isConfirmationValid || loading" @click="confirm">
						{{ loading ? 'A excluir...' : 'Excluir Adquirente' }}
					</AppButton>
				</div>
			</div>
		</div>
	</Teleport>
</template>
