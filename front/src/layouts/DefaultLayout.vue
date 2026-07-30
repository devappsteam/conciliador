<script setup lang="ts">
</script>
<template>
  <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
    <aside class="w-64 bg-white dark:bg-gray-800 border-r hidden md:block">
      </aside>

    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
      <header class="sticky top-0 z-10 flex w-full bg-white dark:bg-gray-800 shadow-sm min-h-16">
         </header>

      <main class="flex-1 p-4 md:p-6 2xl:p-10">
        <slot />
      </main>
    </div>
  </div>
</template>
