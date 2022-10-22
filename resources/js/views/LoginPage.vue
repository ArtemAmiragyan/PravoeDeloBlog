<template>
  <div class="login-page">
    <div class="container">
      <form class="login-page__form" @submit.prevent="login">
        <div class="login-page__form-item">
          <label class="base-label base-label_required">Email</label>
          <input v-model="form.email" :disabled="isLoading" class="base-input" type="email">
        </div>

        <div class="login-page__form-item">
          <label class="base-label base-label_required">Password</label>
          <input v-model="form.password" :disabled="isLoading" class="base-input" type="password">
        </div>

        <button :disabled="isLoading" class="login-page__form-submit" type="submit">
          Sign in
        </button>
        <router-link :to="{ name: 'register' }">
          Sign up
        </router-link>

        <BaseValidationErrors :errors="errors"/>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import User from '@/interfaces/User';
import { useRouter } from 'vue-router';
import BaseValidationErrors from "@/components/base/BaseValidationErrors.vue";
import { VALIDATION_ERROR } from "@/constants/response-codes";

const auth = useAuthStore();

const form = ref<User>({ email: '', password: '' });
const isLoading = ref<boolean>(false);
const router = useRouter();
const errors = ref(null);

const login = async () => {
  try {
    isLoading.value = true;

    errors.value = null;

    const token = await auth.login(form.value);

    if (token) {
      await router.push({ name: 'home' });
    }
  } catch (e: any) {
    if (e.response?.status !== VALIDATION_ERROR) {
      throw e;
    }

    errors.value = e.response.data.errors;
  } finally {
    isLoading.value = false;
  }
};
</script>
