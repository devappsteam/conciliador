import { api } from '@/services/api'
import type { LoginCredentials, UserResponse } from '../types/auth'

export const authService = {
  /**
   * Inicializa a proteção CSRF do Sanctum
   */
  async getCsrfCookie(): Promise<void> {
    await api.get('/sanctum/csrf-cookie')
  },

  /**
   * Submete as credenciais para login
   */
  async login(credentials: LoginCredentials): Promise<void> {
    // O backend validará as credenciais e enviará o cookie de sessão na resposta
    await api.post('/auth/login', credentials)
  },

  /**
   * Destrói a sessão atual no backend
   */
  async logout(): Promise<void> {
    await api.post('/auth/logout')
  },

  /**
   * Busca os dados do usuário autenticado e suas permissões
   */
  async getProfile(): Promise<UserResponse> {
    const response = await api.get<UserResponse>('/auth/me')
    return response.data
  }
}
