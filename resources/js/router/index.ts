import { createWebHistory, createRouter, RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
