import { createWebHistory, createRouter, RouteLocationNormalized } from 'vue-router';
import HomePage from '@/views/HomePage.vue';
import LoginPage from '@/views/LoginPage.vue';
import ProfilePage from '@/views/ProfilePage.vue';
import RegisterPage from '@/views/RegisterPage.vue';
import { useStore } from '@/stores';
import middlewarePipeline from '@/router/middleware/pipeline';
import { authenticated, notAuthenticated } from '@/router/middleware/auth';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomePage,
  },
  {
    path: '/profile',
    name: 'profile',
    meta: {
      middleware: [authenticated],
    },
    component: ProfilePage,
  },
  {
    path: '/login',
    name: 'login',
    meta: {
      middleware: [notAuthenticated],
    },
    component: LoginPage,
  },
  {
    path: '/register',
    name: 'register',
    meta: {
      middleware: [notAuthenticated],
    },
    component: RegisterPage,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to: RouteLocationNormalized, from: RouteLocationNormalized) => {
  const store = useStore();

  try {
    if (store.auth.isAuthenticated) {
      return;
    }

    store.isGlobalLoading = true;

    await store.auth.freshUser(false);
  } catch (e) {
    await store.auth.logout();

    return { name: 'login' };
  } finally {
    store.isGlobalLoading = false;
  }

  const middleware: any[] = to.matched
    .filter((route) => route.meta && route.meta.middleware)
    .map((route) => route.meta.middleware)
    .flat();

  return middlewarePipeline({ to, from, store }, middleware);
});

export default router;
