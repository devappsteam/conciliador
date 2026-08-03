<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import Header from './components/Header.vue'
import Sidebar from './components/Sidebar.vue'

const route = useRoute()
const sidebarOpen = ref<boolean>(false)

watch(
  () => route.name,
  () => {
    sidebarOpen.value = false
  },
)
</script>
<template>
  <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <Sidebar :is-open="sidebarOpen" @close="sidebarOpen = false" />

    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
      <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" />

      <main class="flex-1 p-4 md:p-6 2xl:p-10">
        <div class="max-w-screen-2xl mx-auto">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>
