import axios from 'axios'
import { onlyDigits } from '../utils/document.utils'

export interface CepAddress {
	street: string
	neighborhood: string
	city: string
	state: string
}

/** Consulta pública do ViaCEP (sem necessidade de autenticação/backend) */
export const fetchAddressByCep = async (rawCep: string): Promise<CepAddress | null> => {
	const cep = onlyDigits(rawCep)

	if (cep.length !== 8) {
		return null
	}

	try {
		const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)

		if (response.data?.erro) {
			return null
		}

		return {
			street: response.data.logradouro ?? '',
			neighborhood: response.data.bairro ?? '',
			city: response.data.localidade ?? '',
			state: response.data.uf ?? '',
		}
	} catch {
		return null
	}
}
