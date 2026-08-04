<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
	name: string
	email: string
	avatarUrl?: string
}>()

const initials = computed(() => {
	const [firstWord = ''] = props.name.trim().split(' ')
	return firstWord.slice(0, 1).toUpperCase()
})
</script>

<template>
	<div class="flex">
		<div
			class="w-10 h-10 rounded-full mr-2 bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-semibold overflow-hidden"
			:style="
				avatarUrl
					? {
							backgroundImage: `url(${avatarUrl})`,
							backgroundSize: 'cover',
							backgroundPosition: 'center',
						}
					: undefined
			"
		>
			<span v-if="!avatarUrl">{{ initials }}</span>
		</div>

		<div class="flex flex-col gap-1">
			<div class="font-semibold text-gray-900 dark:text-white">{{ name }}</div>
			<div class="text-xs text-gray-400 dark:text-gray-500">{{ email }}</div>
		</div>
	</div>
</template>
