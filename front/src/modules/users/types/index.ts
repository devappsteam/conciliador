export interface User {
  uuid: string
  avatar_url?: string
  name: string
  email: string
  role?: string
  last_login_at?: string
  created_at: string
  updated_at: string
}

export interface UserFilters {
  search?: string
  role?: string
  page?: number
  per_page?: number
}
