import type { z } from 'zod'
import { createUserFormSchema, updateUserFormSchema } from '../schemas/userForm.schema'

export type CreateUserFormValues = z.infer<typeof createUserFormSchema>
export type UpdateUserFormValues = z.infer<typeof updateUserFormSchema>

export type UserFormSchemaValues = CreateUserFormValues | UpdateUserFormValues
