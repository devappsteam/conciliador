import axios, { AxiosError } from 'axios'
import type { ValidationErrorResponse } from '../types/api'

// O base URL deve vir das variáveis de ambiente (.env) deixarei o .env.example como referência para o que precisa ser configurado.
const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'

export const api = axios.create({
  baseURL,
  withCredentials: true,
  withXSRFToken: true,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
})

/**
 * Interceptor de Resposta
 * Centraliza o tratamento de erros HTTP vindos da API.
 */
api.interceptors.response.use(
  (response) => {
    // Retorna a resposta normalmente se for sucesso (2xx)
    return response
  },
  (error: AxiosError) => {
    if (!error.response) {
      // Erro de rede ou timeout (backend indisponível)
      console.error('Erro de conexão com a API.')
      return Promise.reject(error)
    }

    const { status, data } = error.response

    switch (status) {
      case 401:
        // Sessão expirada ou não autenticado.
        console.warn('Sessão expirada. Redirecionando para login...')
        break

      case 403:
        // Não autorizado (ACL / RBAC)
        console.error('Acesso negado. Você não tem permissão para esta ação.')
        break

      case 422:
        // Erros de Validação
        const validationData = data as ValidationErrorResponse
        console.warn('Erro de validação:', validationData.message)
        // O Front-End deve capturar esses erros nos componentes e exibi-los nos inputs
        break

      case 429:
        // Rate Limiting
        console.error('Muitas requisições. Aguarde um momento e tente novamente.')
        break

      case 500:
        // Erro Interno do Servidor (Logs estarão no Telescope/Horizon da API)
        console.error('Ocorreu um erro interno no servidor.')
        break

      default:
        console.error(`Erro inesperado: ${status}`)
    }

    return Promise.reject(error)
  },
)
