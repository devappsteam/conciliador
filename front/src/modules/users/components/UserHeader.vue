<script setup lang="ts">
import AppButton from '@/components/AppButton.vue'
import AppPageHeader from '@/components/AppPageHeader.vue'

const emit = defineEmits<{
	(event: 'create'): void
}>()
</script>

<template>
	<AppPageHeader
		title="Gestão de Utilizadores"
		subtitle="Controle de acessos, perfis e níveis de permissão do mini ERP."
	>
		<template #actions>
			<AppButton @click="emit('create')">
				<template #icon>
					<svg
						class="w-5 h-5"
						fill="none"
						stroke="currentColor"
						stroke-width="2"
						viewBox="0 0 24 24"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
						/>
					</svg>
				</template>
				Novo Utilizador
			</AppButton>
		</template>
	</AppPageHeader>
</template>
