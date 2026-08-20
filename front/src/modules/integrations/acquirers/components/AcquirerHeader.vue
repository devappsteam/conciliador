<script setup lang="ts">
import AppButton from '@/components/AppButton.vue'
import AppPageHeader from '@/components/AppPageHeader.vue'

const emit = defineEmits<{
	(event: 'create'): void
}>()
</script>

<template>
	<AppPageHeader
		title="Adquirentes"
		subtitle="Cadastro global das adquirentes utilizadas nas conciliações."
	>
		<template #actions>
			<AppButton @click="emit('create')">
				<template #icon>
					<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
					</svg>
				</template>
				Nova Adquirente
			</AppButton>
		</template>
	</AppPageHeader>
</template>
