<script setup lang="ts">
defineProps<{
	previewUrl: string
	errorMessage?: string
	submitting: boolean
}>()

const emit = defineEmits<{
	(event: 'change', value: Event): void
}>()
</script>

<template>
	<div class="md:col-span-2">
		<label for="user-avatar" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
			Avatar
		</label>

		<div class="flex items-center gap-4">
			<div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-gray-100 text-xs text-gray-500 dark:bg-gray-700">
				<img v-if="previewUrl" :src="previewUrl" alt="Pré-visualização do avatar" class="h-full w-full object-cover" />
				<span v-else>Sem foto</span>
			</div>

			<input
				id="user-avatar"
				type="file"
				accept="image/*"
				:disabled="submitting"
				class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-gray-200 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-800 hover:file:bg-gray-300 dark:text-gray-300"
				@change="emit('change', $event)"
			/>
		</div>

		<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
			Formatos aceitos: imagem. Tamanho máximo: 2MB.
		</p>
		<p v-if="errorMessage" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
			{{ errorMessage }}
		</p>
	</div>
</template>
