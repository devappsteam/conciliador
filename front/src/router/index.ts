import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/useAuthStore'
import { authRoutes } from '@/modules/auth/router'
import { dashboardRoutes } from '@/modules/dashboard/router'
import { userRoutes } from '@/modules/users/router'
import { acquirerRoutes } from '@/modules/integrations/acquirers/router'
import { bankRoutes } from '@/modules/integrations/banks/router'
import { brandRoutes } from '@/modules/integrations/brands/router'
import { companyRoutes } from '@/modules/companies/router'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: { name: 'dashboard' },
  },

  // Spread das rotas modulares
  ...authRoutes,
  ...dashboardRoutes,
  ...userRoutes,
  ...acquirerRoutes,
  ...bankRoutes,
  ...brandRoutes,
  ...companyRoutes,

  // Fallback 404
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/layouts/NotFoundView.vue'),
    meta: { layout: 'default', requiresAuth: false },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
  scrollBehavior(_to, _from, savedPosition) {
    return savedPosition || { top: 0 }
  },
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore()

  const requiresAuth = to.meta.requiresAuth !== false
  const guestOnly = to.meta.guestOnly === true

  if (requiresAuth && !authStore.user) {
    try {
      await authStore.fetchProfile()
    } catch (error) {
      console.error('Falha ao restaurar sessão:', error)

      return {
        name: 'auth.login',
        query: {
          redirect: to.fullPath,
        },
      }
    }
  }

  if (requiresAuth && !authStore.isAuthenticated) {
    return {
      name: 'auth.login',
      query: {
        redirect: to.fullPath,
      },
    }
  }

  if (guestOnly && authStore.isAuthenticated) {
    return {
      name: 'dashboard',
    }
  }

  return true
})

router.afterEach((to) => {
  const defaultTitle = 'DevApps - Conciliador'
  document.title = to.meta.title ? `${to.meta.title} | ${defaultTitle}` : defaultTitle
})

export default router
