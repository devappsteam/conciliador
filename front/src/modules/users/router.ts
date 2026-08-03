import type { RouteRecordRaw } from 'vue-router'

export const userRoutes: RouteRecordRaw[] = [
  {
    path: '/settings/users',
    name: 'settings.users',
    component: () => import('./views/UserIndex.vue'),
    meta: {
      layout: 'default',
      requiresAuth: true,
      guestOnly: false,
      title: 'Users',
    },
  },
]
