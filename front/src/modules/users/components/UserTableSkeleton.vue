<script setup lang="ts">
</script>
<template>
  <tr v-for="i in 5" :key="i" class="animate-pulse">
    <td class="px-6 py-4">
      <div class="flex">
        <div class="w-10 h-10 bg-gray-200 rounded-full mr-4"></div>
        <div class="flex flex-col gap-1">
          <div class="h-4 bg-gray-200 rounded-sm w-48 mb-2"></div>
          <div class="h-3 bg-gray-100 rounded-sm w-32"></div>
        </div>
      </div>
    </td>
    <td class="px-6 py-4">
      <div class="h-6 bg-gray-200 rounded-full w-20"></div>
    </td>
    <td class="px-6 py-4">
      <div class="h-4 bg-gray-200 rounded-sm w-28"></div>
    </td>
    <td class="px-6 py-4 text-right">
      <div class="h-8 bg-gray-200 rounded-lg w-16 ml-auto"></div>
    </td>
  </tr>
</template>
