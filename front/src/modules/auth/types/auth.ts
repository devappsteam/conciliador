import type { SingleResource } from '@/types/api'

export interface LoginCredentials {
  email: string
  password: string
}

export interface User {
  uuid: string
  name: string
  email: string
  permissions: string[]
  role?: string
  last_login_at?: string | null
}

export type UserResponse = SingleResource<User>
