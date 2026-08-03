<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const route = useRoute()

const layoutMap: Record<string, any> = {
  auth: AuthLayout,
  default: DefaultLayout,
}

const layoutComponent = computed(() => {
  const layoutName = (route.meta.layout as string) || 'default'
  return layoutMap[layoutName] || DefaultLayout
})
</script>

<template>
  <component :is="layoutComponent">
    <router-view />
  </component>
</template>
