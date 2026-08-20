<script setup lang="ts">
import type { AcquirerFormValues } from '../types'

const props = withDefaults(
	defineProps<{
		values: AcquirerFormValues
		fieldErrors: Partial<Record<keyof AcquirerFormValues, string>>
		submitting: boolean
		isEditMode: boolean
		logoPreviewUrl: string
		globalErrorMessage: string
		formId?: string
	}>(),
	{
		formId: 'acquirer-form',
	},
)

const emit = defineEmits<{
	(event: 'submit'): void
	(event: 'logo-change', value: Event): void
	(event: 'update-field', field: keyof AcquirerFormValues, value: AcquirerFormValues[keyof AcquirerFormValues]): void
}>()
</script>

<template>
	<form :id="props.formId" class="space-y-4 p-6" novalidate @submit.prevent="emit('submit')">
		<div v-if="globalErrorMessage" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert">
			{{ globalErrorMessage }}
		</div>

		<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<label for="acquirer-name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Nome
				</label>
				<input
					id="acquirer-name"
					:value="values.name"
					@input="emit('update-field', 'name', ($event.target as HTMLInputElement).value)"
					type="text"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.name ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="Ex: Cielo"
					:aria-invalid="Boolean(fieldErrors.name)"
					:aria-describedby="fieldErrors.name ? 'acquirer-name-error' : undefined"
				/>
				<p v-if="fieldErrors.name" id="acquirer-name-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.name }}
				</p>
			</div>

			<div>
				<label for="acquirer-slug" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Slug
				</label>
				<input
					id="acquirer-slug"
					:value="values.slug"
					@input="emit('update-field', 'slug', ($event.target as HTMLInputElement).value)"
					type="text"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 font-mono text-sm text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.slug ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="cielo"
					:aria-invalid="Boolean(fieldErrors.slug)"
					:aria-describedby="fieldErrors.slug ? 'acquirer-slug-error' : undefined"
				/>
				<p v-if="fieldErrors.slug" id="acquirer-slug-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.slug }}
				</p>
			</div>

			<div>
				<label for="acquirer-code" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Código
				</label>
				<input
					id="acquirer-code"
					:value="values.code"
					@input="emit('update-field', 'code', ($event.target as HTMLInputElement).value)"
					type="text"
					:disabled="submitting"
					class="w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white"
					:class="fieldErrors.code ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500'"
					placeholder="362"
					:aria-invalid="Boolean(fieldErrors.code)"
					:aria-describedby="fieldErrors.code ? 'acquirer-code-error' : undefined"
				/>
				<p v-if="fieldErrors.code" id="acquirer-code-error" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.code }}
				</p>
			</div>

			<div class="flex items-center gap-3 md:col-span-2">
				<button
					id="acquirer-status"
					type="button"
					role="switch"
					:aria-checked="values.status"
					:disabled="submitting"
					class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors disabled:opacity-50"
					:class="values.status ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'"
					@click="emit('update-field', 'status', !values.status)"
				>
					<span
						class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
						:class="values.status ? 'translate-x-6' : 'translate-x-1'"
					/>
				</button>
				<label for="acquirer-status" class="text-sm font-medium text-gray-700 dark:text-gray-300">
					{{ values.status ? 'Ativo' : 'Inativo' }}
				</label>
			</div>

			<div class="md:col-span-2">
				<label for="acquirer-logo" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
					Logo
				</label>
				<div class="flex items-center gap-4">
					<div class="h-14 w-14 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0">
						<img v-if="logoPreviewUrl" :src="logoPreviewUrl" alt="Pré-visualização do logo" class="h-full w-full object-contain" />
						<span v-else class="text-xs text-gray-400">Sem logo</span>
					</div>
					<input
						id="acquirer-logo"
						type="file"
						accept="image/png,image/jpeg,image/svg+xml,image/webp"
						:disabled="submitting"
						class="text-sm text-gray-600 dark:text-gray-300"
						@change="emit('logo-change', $event)"
					/>
				</div>
				<p class="mt-1 text-xs text-gray-400">Formatos: PNG, JPG, SVG ou WEBP. Máximo: 1MB.</p>
				<p v-if="fieldErrors.logo" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
					{{ fieldErrors.logo }}
				</p>
			</div>
		</div>
	</form>
</template>
