import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/useAuthStore';
import { authRoutes } from '@/modules/auth/router';


const routes: RouteRecordRaw[] = [

  // Spread das rotas modulares
  ...authRoutes,

  // Fallback 404
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/layouts/NotFoundView.vue'),
    meta: { layout: 'auth', requiresAuth: false }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
  scrollBehavior(_to, _from, savedPosition) {
    return savedPosition || { top: 0 }
  }
})

// === Navigation Guards (Segurança e Controle de Fluxo) ===
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  const requiresAuth = to.meta.requiresAuth !== false
  const guestOnly = to.meta.guestOnly === true

  // Lógica de "Refresh da Página" (F5)
  // Se requer autenticação e o store está vazio, tenta buscar o perfil.
  // Como a API usa cookies HttpOnly, o cookie pode estar válido no navegador,
  // mas o store Vue zerou por causa do F5.
  if (requiresAuth && !authStore.isAuthenticated) {
    try {
      await authStore.fetchProfile()
    } catch (error) {
      // Se falhar (401)
      console.error('Falha ao buscar perfil do usuário:', error)
    }
  }

  const isAuth = authStore.isAuthenticated

  if (requiresAuth && !isAuth) {
    next({ name: 'auth.login', query: { redirect: to.fullPath } })
  } else if (guestOnly && isAuth) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

router.afterEach((to) => {
  // Atualiza o título da aba do navegador para UX
  const defaultTitle = 'DevApps - Conciliador'
  document.title = to.meta.title ? `${to.meta.title} | ${defaultTitle}` : defaultTitle
})

export default router
