import type { RouteRecordRaw } from 'vue-router'

export const acquirerRoutes: RouteRecordRaw[] = [
	{
		path: '/integrations/acquirers',
		name: 'integrations.acquirers',
		component: () => import('./views/AcquirerIndex.vue'),
		meta: {
			layout: 'default',
			requiresAuth: true,
			guestOnly: false,
			title: 'Adquirentes',
		},
	},
]
