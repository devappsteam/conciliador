<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
	role?: string
}>()

const normalizedRole = computed(() => (props.role || '--').toLowerCase())

const roleClasses = computed(() => {
  // apenas de teste, será dinamico esses perfis
	const mapping: Record<string, string> = {
		admin:
			'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400',
		bpo: 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400',
		comercial:
			'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-400',
		suporte:
			'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400',
	}

	return mapping[normalizedRole.value] || 'bg-gray-50 text-gray-700 border border-gray-200'
})
</script>

<template>
	<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full" :class="roleClasses">
		{{ role || '--' }}
	</span>
</template>
