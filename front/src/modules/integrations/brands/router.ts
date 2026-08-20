import type { RouteRecordRaw } from 'vue-router'

export const brandRoutes: RouteRecordRaw[] = [
	{
		path: '/integrations/brands',
		name: 'integrations.brands',
		component: () => import('./views/BrandIndex.vue'),
		meta: {
			layout: 'default',
			requiresAuth: true,
			guestOnly: false,
			title: 'Bandeiras',
		},
	},
]
