<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
	role?: string
}>()

const normalizedRole = computed(() => (props.role || '--').toLowerCase())

// Paleta cíclica determinística por role, já que os perfis são cadastrados dinamicamente na API
const COLOR_PALETTE = [
	'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400',
	'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400',
	'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400',
	'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-400',
	'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400',
]

const hashString = (value: string) => {
	let hash = 0
	for (let i = 0; i < value.length; i += 1) {
		hash = (hash << 5) - hash + value.charCodeAt(i)
		hash |= 0
	}
	return Math.abs(hash)
}

const roleClasses = computed(() => {
	if (normalizedRole.value === '--') {
		return 'bg-gray-50 text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'
	}

	return COLOR_PALETTE[hashString(normalizedRole.value) % COLOR_PALETTE.length]
})
</script>

<template>
	<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full" :class="roleClasses">
		{{ role || '--' }}
	</span>
</template>
