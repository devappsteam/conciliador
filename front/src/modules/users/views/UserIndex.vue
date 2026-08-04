<template>
  <div class="space-y-6">
    <UserHeader @create="openCreateModal" />

    <UserFilters
      :search="userStore.currentFilters.search"
      :role="userStore.currentFilters.role"
      @update:search="handleSearchChange"
      @update:role="handleRoleChange"
      @apply-filters="applyFilters"
    />

    <div
      v-if="userStore.error"
      class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm"
    >
      {{ userStore.error }}
    </div>

    <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
      <UserTable
        :users="userStore.users"
        :loading="userStore.loading"
        @edit="editUser"
        @delete="openDeleteModal"
      />

      <AppPagination
        :meta="userStore.meta"
        :loading="userStore.loading"
        @change-page="userStore.changePage"
      />
    </div>

    <UserDeleteModal
      v-model="isDeleteModalOpen"
      :user="selectedUserForDelete"
      :loading="userStore.deleting"
      :error-message="userStore.deleteError"
      @confirm="confirmDeleteUser"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import AppPagination from '@/components/AppPagination.vue'
import { useUserStore } from '../stores/useUserStore'
import type { User } from '../types'
import UserDeleteModal from '../components/UserDeleteModal.vue'
import UserFilters from '../components/UserFilters.vue'
import UserHeader from '../components/UserHeader.vue'
import UserTable from '../components/UserTable.vue'

const userStore = useUserStore()
const isDeleteModalOpen = ref(false)
const selectedUserForDelete = ref<User | null>(null)

const handleSearchChange = (value: string) => {
  userStore.currentFilters.search = value
}

const handleRoleChange = (value: string) => {
  userStore.currentFilters.role = value
}

const applyFilters = () => {
  userStore.fetchUsers({ page: 1 })
}

// Placeholders de controle de fluxo de formulário (A ser conectado aos modais)
const openCreateModal = () => console.log('Abrir modal de criação')
const editUser = (user: User) => console.log('Editar utilizador:', user.uuid)

const openDeleteModal = (uuid: string) => {
  const user = userStore.users.find((item) => item.uuid === uuid)

  if (!user) {
    return
  }

  userStore.clearDeleteError()
  selectedUserForDelete.value = user
  isDeleteModalOpen.value = true
}

const confirmDeleteUser = async () => {
  if (!selectedUserForDelete.value) {
    return
  }

  try {
    await userStore.deleteUser(selectedUserForDelete.value.uuid)
    isDeleteModalOpen.value = false
    selectedUserForDelete.value = null
  } catch {
    // Erro já tratado e exposto por userStore.deleteError
  }
}

watch(isDeleteModalOpen, (isOpen) => {
  if (!isOpen && !userStore.deleting) {
    selectedUserForDelete.value = null
    userStore.clearDeleteError()
  }
})

onMounted(() => {
  userStore.fetchUsers()
})
</script>
