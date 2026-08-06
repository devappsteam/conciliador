<script setup lang="ts">
import { nextTick, onBeforeUnmount, ref, watch } from 'vue'

const props = withDefaults(
	defineProps<{
		modelValue: boolean
		titleId: string
		descriptionId?: string
		closeOnBackdrop?: boolean
		closeOnEscape?: boolean
	}>(),
	{
		closeOnBackdrop: true,
		closeOnEscape: true,
		descriptionId: undefined,
	},
)

const emit = defineEmits<{
	(event: 'update:modelValue', value: boolean): void
}>()

const dialogRef = ref<HTMLElement | null>(null)
let previouslyFocusedElement: HTMLElement | null = null

const focusableSelector = [
	'a[href]',
	'button:not([disabled])',
	'input:not([disabled])',
	'select:not([disabled])',
	'textarea:not([disabled])',
	'[tabindex]:not([tabindex="-1"])',
].join(',')

const getFocusableElements = () => {
	const dialog = dialogRef.value
	if (!dialog) {
		return [] as HTMLElement[]
	}

	return Array.from(dialog.querySelectorAll<HTMLElement>(focusableSelector)).filter(
		(element) => !element.hasAttribute('disabled') && element.offsetParent !== null,
	)
}

const focusInitialElement = async () => {
	await nextTick()

	const focusableElements = getFocusableElements()
	focusableElements[0]?.focus()
	dialogRef.value?.focus()
}

const restoreFocus = () => {
	previouslyFocusedElement?.focus?.()
	previouslyFocusedElement = null
}

const close = () => {
	emit('update:modelValue', false)
}

const handleKeydown = (event: KeyboardEvent) => {
	if (event.key === 'Escape' && props.closeOnEscape) {
		event.preventDefault()
		close()
		return
	}

	if (event.key !== 'Tab') {
		return
	}

	const focusableElements = getFocusableElements()
	if (focusableElements.length === 0) {
		event.preventDefault()
		return
	}

	const firstElement = focusableElements[0]
	const lastElement = focusableElements[focusableElements.length - 1]
	const currentElement = document.activeElement as HTMLElement | null

	if (event.shiftKey && currentElement === firstElement) {
		event.preventDefault()
		lastElement?.focus()
		return
	}

	if (!event.shiftKey && currentElement === lastElement) {
		event.preventDefault()
		firstElement?.focus()
	}
}

watch(
	() => props.modelValue,
	async (isOpen) => {
		if (isOpen) {
			previouslyFocusedElement = document.activeElement as HTMLElement | null
			await focusInitialElement()
			return
		}

		restoreFocus()
	},
	{ immediate: true },
)

onBeforeUnmount(() => {
	restoreFocus()
})
</script>

<template>
	<Teleport to="body">
		<div v-if="modelValue" class="fixed inset-0 z-50">
			<div class="absolute inset-0 bg-gray-900/60" @click="closeOnBackdrop && close()" />
			<div class="relative flex min-h-full items-center justify-center p-4">
				<div
					ref="dialogRef"
					class="w-full outline-none"
					role="dialog"
					aria-modal="true"
					:aria-labelledby="titleId"
					:aria-describedby="descriptionId"
					tabindex="-1"
					@keydown="handleKeydown"
				>
					<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-800">
						<slot />
					</div>
				</div>
			</div>
		</div>
	</Teleport>
</template>
