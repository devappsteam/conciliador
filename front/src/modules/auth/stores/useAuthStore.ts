import { defineStore } from 'pinia'
import { authService } from '../services/authService'
import type { LoginCredentials, User } from '../types/auth'

export const useAuthStore = defineStore('auth', {
  // State
  state: () => ({
    user: null as User | null,
    isLoading: false,
  }),

  // Getters
  getters: {
    isAuthenticated: (state) => state.user !== null,

    hasPermission:
      (state) =>
      (permission: string): boolean => {
        if (!state.user) return false

        return state.user?.permissions.includes(permission)
      },
  },

  // Actions
  actions: {
    async login(credentials: LoginCredentials) {
      this.isLoading = true

      try {
        await authService.getCsrfCookie()
        await authService.login(credentials)
        await this.fetchProfile()
      } finally {
        this.isLoading = false
      }
    },

    async fetchProfile() {
      try {
        const response = await authService.getProfile()
        this.user = response.data
      } catch (error) {
        this.user = null
        throw error
      }
    },

    async logout() {
      this.isLoading = true

      try {
        await authService.logout()
      } finally {
        this.user = null
        this.isLoading = false
      }
    },
  },
})
