import { z } from 'zod'

export interface ImageFileSchemaOptions {
	acceptedTypes: readonly string[]
	maxSizeInBytes: number
	invalidTypeMessage: string
	maxSizeMessage: string
}

export const createImageFileSchema = ({
	acceptedTypes,
	maxSizeInBytes,
	invalidTypeMessage,
	maxSizeMessage,
}: ImageFileSchemaOptions) =>
	z.instanceof(File)
		.refine((file) => acceptedTypes.includes(file.type), { message: invalidTypeMessage })
		.refine((file) => file.size <= maxSizeInBytes, { message: maxSizeMessage })

