import type { RouteRecordRaw } from 'vue-router'

export const companyRoutes: RouteRecordRaw[] = [
	{
		path: '/companies',
		name: 'companies',
		component: () => import('./views/CompanyIndex.vue'),
		meta: {
			layout: 'default',
			requiresAuth: true,
			guestOnly: false,
			title: 'Empresas',
		},
	},
]
