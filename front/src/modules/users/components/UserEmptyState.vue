<script setup lang="ts">
withDefaults(
	defineProps<{
		colspan?: number
		message?: string
	}>(),
	{
		colspan: 4,
		message: 'Nenhum utilizador encontrado com os filtros atuais.',
	},
)
</script>

<template>
	<tr>
		<td :colspan="colspan" class="px-6 py-10 text-center text-gray-400">
			{{ message }}
		</td>
	</tr>
</template>
