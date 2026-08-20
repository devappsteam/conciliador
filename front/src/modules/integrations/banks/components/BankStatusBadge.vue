<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
	active: boolean
}>()

const label = computed(() => (props.active ? 'Ativo' : 'Inativo'))
const classes = computed(() =>
	props.active
		? 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400'
		: 'bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
)
</script>

<template>
	<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full" :class="classes">
		<span class="w-1.5 h-1.5 rounded-full" :class="active ? 'bg-green-500' : 'bg-gray-400'" />
		{{ label }}
	</span>
</template>
