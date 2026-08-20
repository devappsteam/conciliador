/** Remove tudo que não for dígito */
export const onlyDigits = (value: string): string => value.replace(/\D/g, '')

/** Valida CNPJ pelo algoritmo oficial de dígitos verificadores */
export const isValidCnpj = (rawValue: string): boolean => {
	const cnpj = onlyDigits(rawValue)

	if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
		return false
	}

	const calculateDigit = (base: string): number => {
		const weights = base.length === 12 ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2] : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
		const sum = base
			.split('')
			.reduce((acc, digit, index) => acc + Number(digit) * (weights[index] ?? 0), 0)
		const remainder = sum % 11

		return remainder < 2 ? 0 : 11 - remainder
	}

	const firstDigit = calculateDigit(cnpj.slice(0, 12))
	const secondDigit = calculateDigit(cnpj.slice(0, 12) + firstDigit)

	return cnpj === cnpj.slice(0, 12) + String(firstDigit) + String(secondDigit)
}

export const formatCnpj = (rawValue: string): string => {
	const digits = onlyDigits(rawValue).slice(0, 14)

	return digits
		.replace(/^(\d{2})(\d)/, '$1.$2')
		.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
		.replace(/\.(\d{3})(\d)/, '.$1/$2')
		.replace(/(\d{4})(\d)/, '$1-$2')
}

export const formatCep = (rawValue: string): string => {
	const digits = onlyDigits(rawValue).slice(0, 8)

	return digits.replace(/^(\d{5})(\d)/, '$1-$2')
}
