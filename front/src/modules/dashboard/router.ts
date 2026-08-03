import type { RouteRecordRaw } from 'vue-router'

export const dashboardRoutes: RouteRecordRaw[] = [
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('./views/DashboardView.vue'),
    meta: {
      layout: 'default',
      requiresAuth: true,
      title: 'Dashboard',
    },
  },
]
