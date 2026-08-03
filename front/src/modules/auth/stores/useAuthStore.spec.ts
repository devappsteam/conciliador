import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useAuthStore } from './useAuthStore'
import { authService } from '../services/authService'

// Simula a camada de serviço para não disparar chamadas HTTP reais
vi.mock('../services/authService', () => ({
  authService: {
    getCsrfCookie: vi.fn(),
    login: vi.fn(),
    logout: vi.fn(),
    getProfile: vi.fn(),
  },
}))

describe('Auth Store', () => {
  beforeEach(() => {
    // Cria uma nova instância do Pinia antes de cada teste
    setActivePinia(createPinia())
    // Limpa o histórico dos mocks
    vi.clearAllMocks()
  })

  it('deve inicializar com estado vazio e não autenticado', () => {
    const store = useAuthStore()

    expect(store.user).toBeNull()
    expect(store.isAuthenticated).toBe(false)
    expect(store.isLoading).toBe(false)
  })

  it('deve realizar o fluxo de login e popular o estado do usuário corretamente', async () => {
    const store = useAuthStore()

    // Prepara o mock de getProfile para retornar o padrão da APUI envelopado no "data"
    const mockUser = {
      uuid: '019faf39-02bd-715e-acc8-817d8fb17c7c',
      name: 'João BPO',
      email: 'joao@empresa.com',
      permissions: ['conciliation.view'],
    }
    vi.mocked(authService.getProfile).mockResolvedValueOnce({ data: mockUser })

    const credentials = { email: 'joao@empresa.com', password: 'password123' }

    // Executa a action
    await store.login(credentials)

    // Asserções para garantir que o fluxo seguiu a ordem de segurança do Sanctum
    expect(authService.getCsrfCookie).toHaveBeenCalledTimes(1)
    expect(authService.login).toHaveBeenCalledWith(credentials)
    expect(authService.getProfile).toHaveBeenCalledTimes(1)

    // Verifica se o estado foi populado corretamente
    expect(store.user).toEqual(mockUser)
    expect(store.isAuthenticated).toBe(true)
  })

  it('deve retornar true/false corretamente na checagem de permissões (RBAC)', async () => {
    const store = useAuthStore()

    // Fail-safe: Se não há usuário logado, qualquer permissão deve retornar false
    expect(store.hasPermission('conciliation.view')).toBe(false)

    // Mockamos um usuário com uma permissão específica
    store.user = {
      uuid: '019faf39-02bd-715e-acc8-817d8fb17c7c',
      name: 'Maria BPO',
      email: 'maria@empresa.com',
      permissions: ['conciliation.view'],
    }

    // Valida permissão existente
    expect(store.hasPermission('conciliation.view')).toBe(true)
    // Valida permissão inexistente
    expect(store.hasPermission('admin.delete_users')).toBe(false)
  })

  it('deve limpar o estado do usuário ao realizar logout', async () => {
    const store = useAuthStore()

    // Populamos o estado manualmente para o teste
    store.user = {
      uuid: '019faf39-02bd-715e-acc8-817d8fb17c7c',
      name: 'João',
      email: 'joao@empresa.com',
      permissions: [],
    }
    expect(store.isAuthenticated).toBe(true)

    // Simula um logout bem-sucedido na API
    vi.mocked(authService.logout).mockResolvedValueOnce()

    await store.logout()

    // O serviço deve ter sido chamado e o estado limpo
    expect(authService.logout).toHaveBeenCalledTimes(1)
    expect(store.user).toBeNull()
    expect(store.isAuthenticated).toBe(false)
  })

  it('deve limpar o estado do usuário mesmo se a API retornar erro no logout', async () => {
    const store = useAuthStore()
    store.user = {
      uuid: '019faf39-02bd-715e-acc8-817d8fb17c7c',
      name: 'João',
      email: 'joao@empresa.com',
      permissions: [],
    }

    // Simula um erro de rede ou 500 na rota de logout
    vi.mocked(authService.logout).mockRejectedValueOnce(new Error('Network Error'))

    // Executa e captura o erro esperado de rede
    try {
      await store.logout()
    } catch (error) {
      expect((error as Error).message).toBe('Network Error')
    }

    // Para a segurança do frontend, o estado local DEVE ser limpo, independente da falha de rede
    expect(store.user).toBeNull()
    expect(store.isAuthenticated).toBe(false)
  })
})
