<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestão de Utilizadores</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Controle de acessos, perfis e níveis de permissão do mini ERP.
        </p>
      </div>

      <button
        @click="openCreateModal"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-xs cursor-pointer"
      >
        <svg
          class="w-5 h-5 mr-2"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
          />
        </svg>
        Novo Utilizador
      </button>
    </div>

    <div
      class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between"
    >
      <div class="w-full md:w-96 relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <Search class="w-5 h-5" />
        </span>
        <input
          v-model="userStore.currentFilters.search"
          @input="debounceSearch"
          type="text"
          placeholder="Buscar por nome ou e-mail..."
          class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
        />
      </div>

      <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <select
          v-model="userStore.currentFilters.role"
          @change="userStore.fetchUsers({ page: 1 })"
          class="px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-hidden focus:border-blue-500"
        >
          <option value="">Todos os Perfis</option>
          <option value="admin">TI / Admin</option>
          <option value="bpo">BPO Financeiro</option>
          <option value="comercial">Comercial</option>
          <option value="suporte">Suporte</option>
        </select>
      </div>
    </div>

    <div
      v-if="userStore.error"
      class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm"
    >
      {{ userStore.error }}
    </div>

    <div
      class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-gray-900 text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700"
            >
              <th class="px-6 py-4">Usuário</th>
              <th class="px-6 py-4">Perfil</th>
              <th class="px-6 py-4">Data de Cadastro</th>
              <th class="px-6 py-4 text-right">Ações</th>
            </tr>
          </thead>
          <tbody
            class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300"
          >
            <template v-if="userStore.loading">
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
                <td class="px-6 py-4"><div class="h-6 bg-gray-200 rounded-full w-20"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded-sm w-28"></div></td>
                <td class="px-6 py-4 text-right">
                  <div class="h-8 bg-gray-200 rounded-lg w-16 ml-auto"></div>
                </td>
              </tr>
            </template>

            <template v-else-if="userStore.users.length > 0">
              <tr
                v-for="user in userStore.users"
                :key="user.uuid"
                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="flex">
                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-2" :style="{ backgroundImage: `url(${user.avatar_url})`, backgroundSize: 'cover', backgroundPosition: 'center' }"></div>
                    <div class="flex flex-col gap-1">
                      <div class="font-semibold text-gray-900 dark:text-white">{{ user.name }}</div>
                      <div class="text-xs text-gray-400 dark:text-gray-500">{{ user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full"
                    :class="roleBadgeClasses(user.role ?? '--')"
                  >
                    {{ user.role ?? '--' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                  {{ new Date(user.created_at).toLocaleDateString('pt-PT') }}
                </td>
                <td class="px-6 py-4 text-right whitespace-nowrap">
                  <button
                    @click="editUser(user)"
                    class="text-blue-600 hover:text-blue-900 font-medium mr-3 cursor-pointer"
                  >
                  <Edit class="w-4 h-4 inline-block" />
                  </button>
                  <button
                    @click="deleteUser(user.uuid)"
                    class="text-red-600 hover:text-red-900 font-medium cursor-pointer"
                  >
                    <Trash class="w-4 h-4 inline-block" />
                  </button>
                </td>
              </tr>
            </template>

            <tr v-else>
              <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                Nenhum utilizador encontrado com os filtros atuais.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div
        v-if="userStore.meta && userStore.meta.last_page > 1"
        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/20"
      >
        <span class="text-xs text-gray-500">
          Mostrando {{ userStore.meta.from }} a {{ userStore.meta.to }} de
          {{ userStore.meta.total }} registros
        </span>
        <div class="inline-flex space-x-1">
          <button
            @click="userStore.changePage(userStore.meta.current_page - 1)"
            :disabled="userStore.meta.current_page === 1 || userStore.loading"
            class="px-3 py-1 text-xs border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 disabled:opacity-50 cursor-pointer"
          >
            Anterior
          </button>
          <button
            @click="userStore.changePage(userStore.meta.current_page + 1)"
            :disabled="
              userStore.meta.current_page === userStore.meta.last_page || userStore.loading
            "
            class="px-3 py-1 text-xs border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 disabled:opacity-50 cursor-pointer"
          >
            Próximo
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useUserStore } from '../stores/useUserStore'
import { useAuthStore } from '@/modules/auth/stores/useAuthStore'
import { Edit, Search, Trash } from '@lucide/vue'
import type { User } from '../types'

const userStore = useUserStore()
const authStore = useAuthStore()

let debounceTimer: ReturnType<typeof setTimeout>

const debounceSearch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    userStore.fetchUsers({ page: 1 })
  }, 400)
}

const roleBadgeClasses = (role: string) => {
  const mapping: Record<string, string> = {
    admin: 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400',
    bpo: 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400',
    comercial:
      'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-400',
    suporte:
      'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400',
  }
  return mapping[role.toLowerCase()] || 'bg-gray-50 text-gray-700 border border-gray-200'
}

// Placeholders de controle de fluxo de formulário (A ser conectado aos modais)
const openCreateModal = () => console.log('Abrir modal de criação')
const editUser = (user: User) => console.log('Editar utilizador:', user.uuid)
const deleteUser = async (uuid: string) => {
  if (confirm('Tem certeza de que deseja remover este utilizador da base do ERP?')) {
    // Implementar fluxo de remoção chamando service + alert de sucesso
    console.log('Remover id:', uuid)
  }
}

onMounted(() => {
  userStore.fetchUsers()
})
</script>
