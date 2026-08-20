<script setup lang="ts">
import { COMPANY_STATUS_OPTIONS } from '../constants/company.constants'
import { formatCep, formatCnpj } from '../utils/document.utils'
import type { CompanyFormValues } from '../types'

const props = withDefaults(
	defineProps<{
		values: CompanyFormValues
		fieldErrors: Partial<Record<keyof CompanyFormValues, string>>
		submitting: boolean
		isEditMode: boolean
		loadingCep: boolean
		globalErrorMessage: string
		formId?: string
	}>(),
	{
		formId: 'company-form',
	},
)

const emit = defineEmits<{
	(event: 'submit'): void
	(event: 'cep-blur'): void
	(event: 'update-field', field: keyof CompanyFormValues, value: CompanyFormValues[keyof CompanyFormValues]): void
}>()

const inputClass = (hasError: boolean) =>
	[
		'w-full rounded-lg border bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition-colors dark:bg-gray-700/50 dark:text-white',
		hasError
			? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500'
			: 'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500',
	].join(' ')

const labelClass = 'mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300'
const errorClass = 'mt-1.5 text-xs font-medium text-red-600 dark:text-red-400'
</script>

<template>
	<form :id="props.formId" class="space-y-6 p-6" novalidate @submit.prevent="emit('submit')">
		<div v-if="globalErrorMessage" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert">
			{{ globalErrorMessage }}
		</div>

		<section class="space-y-4">
			<h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Identificação</h3>
			<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
				<div>
					<label for="company-document" :class="labelClass">CNPJ</label>
					<input
						id="company-document"
						:value="formatCnpj(values.document)"
						@input="emit('update-field', 'document', ($event.target as HTMLInputElement).value)"
						type="text"
						inputmode="numeric"
						maxlength="18"
						:disabled="submitting || isEditMode"
						:class="inputClass(Boolean(fieldErrors.document))"
						placeholder="00.000.000/0000-00"
						:aria-invalid="Boolean(fieldErrors.document)"
					/>
					<p v-if="fieldErrors.document" :class="errorClass">{{ fieldErrors.document }}</p>
				</div>

				<div>
					<label for="company-status" :class="labelClass">Status</label>
					<select
						id="company-status"
						:value="values.status"
						@change="emit('update-field', 'status', ($event.target as HTMLSelectElement).value as CompanyFormValues['status'])"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.status))"
					>
						<option v-for="option in COMPANY_STATUS_OPTIONS" :key="option.value" :value="option.value">
							{{ option.label }}
						</option>
					</select>
				</div>

				<div class="md:col-span-2">
					<label for="company-corporate-name" :class="labelClass">Razão Social</label>
					<input
						id="company-corporate-name"
						:value="values.corporate_name"
						@input="emit('update-field', 'corporate_name', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.corporate_name))"
						placeholder="Razão social completa"
						:aria-invalid="Boolean(fieldErrors.corporate_name)"
					/>
					<p v-if="fieldErrors.corporate_name" :class="errorClass">{{ fieldErrors.corporate_name }}</p>
				</div>

				<div>
					<label for="company-trade-name" :class="labelClass">Nome Fantasia</label>
					<input
						id="company-trade-name"
						:value="values.trade_name"
						@input="emit('update-field', 'trade_name', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.trade_name))"
					/>
				</div>

				<div>
					<label for="company-state-registration" :class="labelClass">Inscrição Estadual</label>
					<input
						id="company-state-registration"
						:value="values.state_registration"
						@input="emit('update-field', 'state_registration', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.state_registration))"
					/>
				</div>

				<div>
					<label for="company-municipal-registration" :class="labelClass">Inscrição Municipal</label>
					<input
						id="company-municipal-registration"
						:value="values.municipal_registration"
						@input="emit('update-field', 'municipal_registration', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.municipal_registration))"
					/>
				</div>
			</div>
		</section>

		<section class="space-y-4">
			<h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Contato</h3>
			<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
				<div>
					<label for="company-email" :class="labelClass">E-mail</label>
					<input
						id="company-email"
						:value="values.email"
						@input="emit('update-field', 'email', ($event.target as HTMLInputElement).value)"
						type="email"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.email))"
						placeholder="contato@empresa.com"
						:aria-invalid="Boolean(fieldErrors.email)"
					/>
					<p v-if="fieldErrors.email" :class="errorClass">{{ fieldErrors.email }}</p>
				</div>

				<div>
					<label for="company-phone" :class="labelClass">Telefone</label>
					<input
						id="company-phone"
						:value="values.phone"
						@input="emit('update-field', 'phone', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.phone))"
					/>
				</div>
			</div>
		</section>

		<section class="space-y-4">
			<h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Endereço</h3>
			<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
				<div>
					<label for="company-zip-code" :class="labelClass">CEP</label>
					<input
						id="company-zip-code"
						:value="formatCep(values.zip_code)"
						@input="emit('update-field', 'zip_code', ($event.target as HTMLInputElement).value)"
						@blur="emit('cep-blur')"
						type="text"
						inputmode="numeric"
						maxlength="9"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.zip_code))"
						placeholder="00000-000"
						:aria-invalid="Boolean(fieldErrors.zip_code)"
					/>
					<p v-if="fieldErrors.zip_code" :class="errorClass">{{ fieldErrors.zip_code }}</p>
					<p v-if="loadingCep" class="mt-1.5 text-xs text-gray-400">A buscar endereço...</p>
				</div>

				<div class="md:col-span-2">
					<label for="company-street" :class="labelClass">Logradouro</label>
					<input
						id="company-street"
						:value="values.street"
						@input="emit('update-field', 'street', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.street))"
						:aria-invalid="Boolean(fieldErrors.street)"
					/>
					<p v-if="fieldErrors.street" :class="errorClass">{{ fieldErrors.street }}</p>
				</div>

				<div>
					<label for="company-number" :class="labelClass">Número</label>
					<input
						id="company-number"
						:value="values.number"
						@input="emit('update-field', 'number', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.number))"
					/>
				</div>

				<div>
					<label for="company-complement" :class="labelClass">Complemento</label>
					<input
						id="company-complement"
						:value="values.complement"
						@input="emit('update-field', 'complement', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.complement))"
					/>
				</div>

				<div>
					<label for="company-neighborhood" :class="labelClass">Bairro</label>
					<input
						id="company-neighborhood"
						:value="values.neighborhood"
						@input="emit('update-field', 'neighborhood', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.neighborhood))"
					/>
				</div>

				<div>
					<label for="company-city" :class="labelClass">Cidade</label>
					<input
						id="company-city"
						:value="values.city"
						@input="emit('update-field', 'city', ($event.target as HTMLInputElement).value)"
						type="text"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.city))"
					/>
				</div>

				<div>
					<label for="company-state" :class="labelClass">UF</label>
					<input
						id="company-state"
						:value="values.state"
						@input="emit('update-field', 'state', ($event.target as HTMLInputElement).value.toUpperCase())"
						type="text"
						maxlength="2"
						:disabled="submitting"
						:class="inputClass(Boolean(fieldErrors.state))"
					/>
				</div>
			</div>
		</section>
	</form>
</template>
