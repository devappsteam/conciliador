import type { USER_ROLES } from '../constants/user.constants'

export interface User {
  uuid: string
  avatar_url?: string
  name: string
  email: string
  role?: UserRole
  last_login_at?: string
  created_at: string
  updated_at: string
}

export interface UserFilters {
  search?: string
  role?: UserRole | ''
  page?: number
  per_page?: number
}

export type UserRole = (typeof USER_ROLES)[number]

export type UserFormField = 'name' | 'email' | 'role' | 'password' | 'password_confirmation' | 'avatar'

export interface UserFormValues {
  name: string
  email: string
  role: UserRole | ''
  password: string
  password_confirmation: string
  avatar: File | null
}

export type { CreateUserFormValues, UpdateUserFormValues, UserFormSchemaValues } from './user-form.types'

export interface CreateUserPayload {
  name: string
  email: string
  role: UserRole
  password: string
  password_confirmation: string
  avatar?: File | null
}

export interface UpdateUserPayload {
  name: string
  email: string
  role: UserRole
  password?: string
  password_confirmation?: string
  avatar?: File | null
}
