import { z } from 'zod'

export const uuidSchema = z.string().uuid({
  version: 'v4',
  message: 'Informe um UUID válido.'
})
