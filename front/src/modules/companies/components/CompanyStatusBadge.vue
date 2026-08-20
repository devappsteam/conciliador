<script setup lang="ts">
import { computed } from 'vue'
import { COMPANY_STATUS_BADGE_CLASSES, COMPANY_STATUS_LABELS } from '../constants/company.constants'
import type { CompanyStatus } from '../types'

const props = defineProps<{
	status: CompanyStatus
}>()

const label = computed(() => COMPANY_STATUS_LABELS[props.status] ?? props.status)
const classes = computed(
	() => COMPANY_STATUS_BADGE_CLASSES[props.status] ?? 'bg-gray-50 text-gray-700 border border-gray-200',
)
</script>

<template>
	<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full" :class="classes">
		{{ label }}
	</span>
</template>
