<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../stores/useAuthStore'
import type { ValidationErrorResponse } from '@/types/api'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

const errors = ref<Record<string, string[]>>({})
const globalErrorMessage = ref<string>('')

const handleSubmit = async () => {
  errors.value = {}
  globalErrorMessage.value = ''

  if (!form.email || !form.password) {
    globalErrorMessage.value = 'Por favor, preencha todos os campos obrigatórios.'
    return
  }

  try {
    await authStore.login({
      email: form.email,
      password: form.password,
    })

    const redirectPath = (route.query.redirect as string) || '/'

    await router.push(redirectPath)
  } catch (error) {
    if (axios.isAxiosError(error)) {
      const response = error.response

      if (response?.status === 422) {
        const validationData = response.data as ValidationErrorResponse
        errors.value = validationData.errors
      } else if (response?.status === 401) {
        globalErrorMessage.value = 'As credenciais introduzidas estão incorretas.'
      } else if (response?.status === 429) {
        globalErrorMessage.value =
          'Muitas tentativas. Por favor, aguarde um momento antes de tentar novamente.'
      } else {
        globalErrorMessage.value =
          'Não foi possível estabelecer ligação com o servidor. Tente mais tarde.'
      }
    } else {
      globalErrorMessage.value = 'Ocorreu um erro inesperado. Por favor, tente novamente.'
    }
  }
}
</script>

<template>
  <div
    class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700"
  >
    <div class="mb-8 text-center">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
        <img src="https://confiraaki.com.br/wp-content/uploads/2024/12/logo_CONFIRA_AKI-300x130-1-1.png" class="h-20 mx-auto" alt="logo" />
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Informe suas credenciais para acessar o sistema.
      </p>
    </div>

    <div
      v-if="globalErrorMessage"
      class="mb-6 p-4 bg-red-50 dark:bg-red-950/30 border-l-4 border-red-500 text-sm text-red-700 dark:text-red-400 rounded-r-md"
      role="alert"
    >
      {{ globalErrorMessage }}
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-4" novalidate>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          E-mail <span class="text-red-500">*</span>
        </label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          autocomplete="email"
          autofocus
          required
          :disabled="authStore.isLoading"
          class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
          :class="[
            errors.email
              ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500'
              : 'border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-1 focus:ring-primary',
          ]"
        />
        <p v-if="errors.email" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
          {{ errors.email[0] }}
        </p>
      </div>

      <div>
        <div class="flex items-center justify-between mb-2">
          <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Senha <span class="text-red-500">*</span>
          </label>
        </div>
        <input
          id="password"
          v-model="form.password"
          type="password"
          autocomplete="current-password"
          required
          :disabled="authStore.isLoading"
          class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white border rounded-lg outline-none transition-colors"
          :class="[
            errors.password
              ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500'
              : 'border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-1 focus:ring-primary',
          ]"
        />
        <p v-if="errors.password" class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">
          {{ errors.password[0] }}
        </p>
      </div>

      <button
        type="submit"
        :disabled="authStore.isLoading"
        class="w-full flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 dark:disabled:bg-blue-800/80 rounded-lg transition-colors cursor-pointer disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-sm"
      >
        <svg
          v-if="authStore.isLoading"
          class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          ></circle>
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          ></path>
        </svg>

        <span>{{ authStore.isLoading ? 'A autenticar...' : 'Entrar' }}</span>
      </button>
    </form>
  </div>
</template>
