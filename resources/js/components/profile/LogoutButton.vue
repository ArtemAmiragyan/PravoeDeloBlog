<template>
  <button :disabled="isLoading" @click="handleSignOut" class="logout-button">
    Sign Out
  </button>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { ref } from "vue";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const isLoading = ref<boolean>(false);
const router = useRouter();

const handleSignOut = async () => {
  try {
    isLoading.value = true;

    auth.logout();

    await router.push({ name: 'login' })
  } finally {
    isLoading.value = false;
  }
};
</script>
