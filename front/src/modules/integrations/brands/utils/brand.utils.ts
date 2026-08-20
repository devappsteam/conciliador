import type { Brand, BrandFormValues, CreateBrandPayload, UpdateBrandPayload } from '../types'

export const createInitialBrandFormValues = (): BrandFormValues => ({
	name: '',
	code: '',
	status: true,
	logo: null,
})

export const createBrandFormValuesFromBrand = (brand: Brand): BrandFormValues => ({
	name: brand.name ?? '',
	code: brand.code ?? '',
	status: brand.status ?? true,
	logo: null,
})

export const buildBrandPayload = (values: BrandFormValues): CreateBrandPayload | UpdateBrandPayload => ({
	name: values.name.trim(),
	code: values.code.trim(),
	status: values.status,
	logo: values.logo ?? null,
})
