<script setup lang="ts">
import AppButton from '@/components/AppButton.vue'
import AppPageHeader from '@/components/AppPageHeader.vue'

const emit = defineEmits<{
	(event: 'create'): void
}>()
</script>

<template>
	<AppPageHeader title="Empresas" subtitle="Cadastro e gestão dos clientes (empresas) do ERP.">
		<template #actions>
			<AppButton @click="emit('create')">
				<template #icon>
					<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
					</svg>
				</template>
				Nova Empresa
			</AppButton>
		</template>
	</AppPageHeader>
</template>
