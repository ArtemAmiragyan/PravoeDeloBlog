<template>
  <h1>Create New Article</h1>
  <BaseArticleForm :is-disabled="isSaving" @submit="handleSubmit"/>
  <BaseValidationErrors :errors="errors"/>
</template>

<script setup lang="ts">
import BaseArticleForm from '@/components/articles/BaseArticleForm.vue';
import { ref } from 'vue';
import { useArticleStore } from '@/stores/article';
import Article from '@/interfaces/article/Article';
import { VALIDATION_ERROR } from '@/constants/response-codes';
import BaseValidationErrors from '@/components/base/BaseValidationErrors.vue';
import { useRouter } from 'vue-router';

const articleStore = useArticleStore();
const isSaving = ref<boolean>(false);
const errors = ref(null);
const router = useRouter();

const handleSubmit = async (data: Article) => {
  try {
    isSaving.value = true;

    await articleStore.create(data);

    await router.push({ name: 'my-articles' });
  } catch (e: any) {
    if (e.response?.status !== VALIDATION_ERROR) {
      throw e;
    }

    errors.value = e.response.data.errors;
  } finally {
    isSaving.value = false;
  }
};
</script>
