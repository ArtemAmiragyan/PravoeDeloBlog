import { defineStore } from 'pinia';
import UserModel from '@/models/UserModel';
import { computed, ref } from 'vue';
import axios from '@/plugins/axios';
import User from '@/interfaces/User';
import { TOKEN } from '@/constants/cookie-keys';
import { getCookie, removeCookie, setCookie } from 'typescript-cookie';

export interface State {
  user: UserModel
}

export function createDefaultUser(): UserModel {
  return {
    id: null,
    email: '',
    name: '',
  };
}

export const useAuthStore = defineStore('auth/user', () => {
  const user = ref<UserModel>(createDefaultUser());

  const isAuthenticated = computed(() => (!!user.value.id));

  async function login(formData: User) {
    const token = getCookie(TOKEN);

    if (token) {
      return token;
    }

    await freshCsrf();
    const { data } = await axios.post('login', formData);

    user.value = data.user;

    axios.defaults.headers.Authorization = `Bearer ${data.token}`;
    setCookie(TOKEN, data.token);

    return data.token;
  }

  async function register(formData: User) {
    await freshCsrf();
    const { data } = await axios.post('register', formData);

    user.value = data.user;

    axios.defaults.headers.Authorization = `Bearer ${data.token}`;
    setCookie(TOKEN, data.token);

    return data.token;
  }

  async function freshUser(isFreshCsrf = true) {
    if (!getCookie(TOKEN)) {
      return;
    }

    if (isFreshCsrf) {
      await freshCsrf();
    }

    const { data } = await axios.get('user');

    user.value = data;
  }

  async function logout() {
    await freshCsrf();
    await axios.post('logout');

    user.value = createDefaultUser();

    removeCookie('token');
  }

  async function freshCsrf() {
    axios.defaults.baseURL = '';

    await axios.get('/sanctum/csrf-cookie');

    axios.defaults.baseURL = '/api/';
  }

  return {
    user,
    isAuthenticated,
    login,
    freshUser,
    logout,
    register,
  };
});
