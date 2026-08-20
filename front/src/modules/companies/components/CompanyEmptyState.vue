<script setup lang="ts">
const props = withDefaults(
	defineProps<{
		colspan?: number
		message?: string
	}>(),
	{
		colspan: 4,
		message: 'Nenhuma empresa encontrada com os filtros atuais.',
	},
)
</script>

<template>
	<tr>
		<td :colspan="props.colspan" class="px-6 py-10 text-center text-gray-400">
			{{ props.message }}
		</td>
	</tr>
</template>
