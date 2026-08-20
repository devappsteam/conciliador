import type { RouteRecordRaw } from 'vue-router'

export const bankRoutes: RouteRecordRaw[] = [
	{
		path: '/integrations/banks',
		name: 'integrations.banks',
		component: () => import('./views/BankIndex.vue'),
		meta: {
			layout: 'default',
			requiresAuth: true,
			guestOnly: false,
			title: 'Bancos',
		},
	},
]
