import { defineStore } from 'pinia';
import { useAuthStore } from '@/stores/auth';
import { ref } from 'vue';

export const useStore = defineStore('store', () => {
  const auth = useAuthStore();
  const isGlobalLoading = ref<boolean>(false);

  return {
    auth,
    isGlobalLoading,
  };
});
