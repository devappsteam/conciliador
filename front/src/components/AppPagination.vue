<script setup lang="ts">
import { computed } from 'vue'
import AppButton from '@/components/AppButton.vue'
import type { PaginationMeta } from '@/types/api'

const props = defineProps<{
  meta: PaginationMeta | null
  loading?: boolean
}>()

const emit = defineEmits<{
  (event: 'change-page', page: number): void
}>()

const canGoPrevious = computed(
  () => !!props.meta && props.meta.current_page > 1 && !props.loading,
)

const canGoNext = computed(
  () => !!props.meta && props.meta.current_page < props.meta.last_page && !props.loading,
)

const goToPreviousPage = () => {
  if (!props.meta || !canGoPrevious.value) {
    return
  }

  emit('change-page', props.meta.current_page - 1)
}

const goToNextPage = () => {
  if (!props.meta || !canGoNext.value) {
    return
  }

  emit('change-page', props.meta.current_page + 1)
}
</script>

<template>
  <div
    v-if="meta && meta.last_page > 1"
    class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/20"
  >
    <span class="text-xs text-gray-500">
      Mostrando {{ meta.from }} a {{ meta.to }} de {{ meta.total }} registros
    </span>

    <div class="inline-flex space-x-1">
      <AppButton variant="outline" size="sm" :disabled="!canGoPrevious" @click="goToPreviousPage">
        Anterior
      </AppButton>

      <AppButton variant="outline" size="sm" :disabled="!canGoNext" @click="goToNextPage">
        Próximo
      </AppButton>
    </div>
  </div>
</template>
