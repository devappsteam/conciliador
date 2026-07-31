<script setup lang="ts">
import { useAuthStore } from '@/modules/auth/stores/useAuthStore';

defineProps<{ isOpen: boolean }>()
defineEmits<{ (e: 'close'): void }>()

const authStore = useAuthStore()

const DashboardIcon = { template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>` }
const CardsIcon = { template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>` }
const UsersIcon = { template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>` }

interface MenuItem {
  label: string
  routeName: string
  icon: any
  permission?: string
}

interface MenuGroup {
  name: string
  items: MenuItem[]
}

const menuStructure: MenuGroup[] = [
  {
    name: 'Geral',
    items: [
      { label: 'Dashboard', routeName: 'dashboard', icon: DashboardIcon }
    ]
  },
  {
    name: 'Conciliador',
    items: [
      { label: 'Vendas Adquirentes', routeName: 'dashboard', icon: CardsIcon }
    ]
  },
  {
    name: 'Administração',
    items: [
      { label: 'Utilizadores', routeName: 'dashboard', icon: UsersIcon }
    ]
  }
]
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 bg-black/40 md:hidden transition-opacity" @click="$emit('close')"></div>

  <aside
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-gray-900 text-gray-300 border-r border-gray-800 transition-transform duration-300 md:static md:translate-x-0"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-800">
      <span class="text-lg font-bold text-white tracking-wider">DevApps Conciliador</span>
      <button class="md:hidden text-gray-400 hover:text-white" @click="$emit('close')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
      <div v-for="group in menuStructure" :key="group.name">
        <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
          {{ group.name }}
        </p>

        <div class="space-y-1 mb-4">
          <template v-for="item in group.items" :key="item.routeName">
            <router-link v-if="!item.permission"
              :to="{ name: item.routeName }" custom v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group" :class="isActive
                  ? 'bg-blue-600 text-white font-semibold'
                  : 'hover:bg-gray-800 hover:text-white'">
                <component :is="item.icon" class="w-5 h-5 mr-3 transition-colors"
                  :class="isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-300'" />
                <span>{{ item.label }}</span>
              </a>
            </router-link>
          </template>
        </div>
      </div>
    </nav>
  </aside>
</template>
