export interface Role {
  uuid: string
  name: string
}

export interface User {
  uuid: string
  avatar_url?: string
  name: string
  email: string
  role?: Role[]
  last_login_at?: string
  created_at: string
  updated_at: string
}

export interface UserFilters {
  search?: string
  role_uuid?: string
  page?: number
  per_page?: number
}

export type UserFormField = 'name' | 'email' | 'role_uuid' | 'password' | 'password_confirmation' | 'avatar'

export interface UserFormValues {
  name: string
  email: string
  role_uuid: string
  password: string
  password_confirmation: string
  avatar: File | null
}

export type { CreateUserFormValues, UpdateUserFormValues, UserFormSchemaValues } from './user-form.types'

export interface CreateUserPayload {
  name: string
  email: string
  role_uuid: string
  password: string
  password_confirmation: string
  avatar?: File | null
}

export interface UpdateUserPayload {
  name: string
  email: string
  role_uuid: string
  password?: string
  password_confirmation?: string
  avatar?: File | null
}
