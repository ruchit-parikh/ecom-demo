import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/home/Home.vue'
import { useUserStore } from '@/stores/user'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: Home, name: 'home' },
    {
      path: '/orders',
      meta: { requiresAuth: true },
      component: () => import('@/pages/orders/Orders.vue'),
      name: 'orders'
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    const userStore = useUserStore()

    if (!userStore.user) {
      next({ name: 'home' })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
