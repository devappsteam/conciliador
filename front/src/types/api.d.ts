// Tipagens para respostas paginadas do Resource
export interface PaginationLinks {
  first: string
  last: string
  prev: string | null
  next: string | null
}

export interface PaginationMeta {
  current_page: number
  from: number | null
  last_page: number
  per_page: number
  to: number | null
  total: number
}

// Resposta para Conllections (Listas)
export interface PaginatedResource<T> {
  data: T[]
  links: PaginationLinks
  meta: PaginationMeta
}

// Resposta para Registro Único
export interface SingleResource<T> {
  data: T
}

// Tipagem padronizada para erros de validação (FormRequest da API)
export interface ValidationErrorResponse {
  message: string
  errors: Record<string, string[]>
}
