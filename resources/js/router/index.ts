import { createWebHistory, createRouter, RouteLocationNormalized } from 'vue-router';
import LoginPage from '@/views/LoginPage.vue';
import ProfilePage from '@/views/ProfilePage.vue';
import RegisterPage from '@/views/RegisterPage.vue';
import ArticleShowPage from '@/views/articles/ArticleShowPage.vue';
import ArticleEditPage from '@/views/articles/ArticleEditPage.vue';
import ArticleCreatePage from '@/views/articles/ArticleCreatePage.vue';
import ArticlesPage from '@/views/articles/ArticlesPage.vue';
import UserArticlesPage from '@/views/articles/UserArticlesPage.vue';
import { useStore } from '@/stores';
import middlewarePipeline from '@/router/middleware/pipeline';
import { authenticated, notAuthenticated } from '@/router/middleware/auth';

const routes = [
  {
    path: '/',
    name: 'home',
    component: ArticlesPage,
    meta: {
      middleware: [authenticated],
    },
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
    path: '/my-articles',
    name: 'my-articles',
    meta: {
      middleware: [authenticated],
    },
    component: UserArticlesPage,
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
  {
    path: '/articles/:id',
    name: 'articles.show',
    component: ArticleShowPage,
    props: true,
    meta: {
      middleware: [authenticated],
    },
  },
  {
    path: '/articles/create',
    name: 'articles.create',
    component: ArticleCreatePage,
    meta: {
      middleware: [authenticated],
    },
  },
  {
    path: '/articles/:id/edit',
    name: 'articles.edit',
    component: ArticleEditPage,
    props: true,
    meta: {
      middleware: [authenticated],
    },
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
