import { ref } from 'vue'
import { roleService } from '../services/roleService'
import type { Role } from '../types'

// Cache no nível do módulo: evita refetch a cada abertura do form/filtro
const roles = ref<Role[]>([])
const loading = ref(false)
const loaded = ref(false)
const error = ref('')

export const useRoleOptions = () => {
	const fetchRoles = async (force = false) => {
		if (loaded.value && !force) {
			return
		}

		loading.value = true
		error.value = ''

		try {
			roles.value = await roleService.getAll()
			loaded.value = true
		} catch {
			error.value = 'Não foi possível carregar os perfis disponíveis.'
		} finally {
			loading.value = false
		}
	}

	return { roles, loading, error, fetchRoles }
}
