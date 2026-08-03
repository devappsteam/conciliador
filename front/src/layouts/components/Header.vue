<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/useAuthStore'
import { LogOut} from '@lucide/vue';

defineEmits<{ (e: 'toggle-sidebar'): void }>()

const router = useRouter()
const authStore = useAuthStore()

const handleLogout = async () => {
  try {
    await authStore.logout()
    await router.push({ name: 'auth.login' })
  } catch (error) {
    console.error('Erro ao terminar sessão:', error)
    await router.push({ name: 'auth.login' })
  }
}
</script>

<template>
  <header
    class="sticky top-0 z-40 flex items-center justify-between w-full h-16 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
  >
    <button
      class="text-gray-500 focus:outline-none md:hidden hover:text-gray-700 dark:hover:text-gray-300"
      @click="$emit('toggle-sidebar')"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16"
        />
      </svg>
    </button>

    <div class="hidden md:block text-sm text-gray-500"></div>

    <div class="flex items-center space-x-4">
      <div class="text-right hidden sm:block">
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
          {{ authStore.user?.name || 'Utilizador' }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">
          {{ authStore.user?.role || 'Operador' }}
        </p>
      </div>

      <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

      <button
        @click="handleLogout"
        title="Terminar Sessão"
        class="flex items-center justify-center p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer"
      >
        <LogOut class="w-5 h-5" />
      </button>
    </div>
  </header>
</template>
