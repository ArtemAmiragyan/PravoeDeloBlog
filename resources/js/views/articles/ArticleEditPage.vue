<template>
  <h1>Edit Article</h1>
  <BaseLoader v-if="isLoading"/>
  <BaseArticleForm v-else :is-disabled="isSaving" @submit="handleSubmit" :article="item"/>
  <BaseValidationErrors :errors="errors"/>
</template>

<script setup lang="ts">
import BaseArticleForm from '@/components/articles/BaseArticleForm.vue';
import { defineProps, ref } from 'vue';
import { useArticleStore } from '@/stores/article';
import Article from '@/interfaces/article/Article';
import { VALIDATION_ERROR } from '@/constants/response-codes';
import BaseValidationErrors from '@/components/base/BaseValidationErrors.vue';
import { useRouter } from 'vue-router';
import BaseLoader from '@/components/base/BaseLoader.vue';

const articleStore = useArticleStore();
const isLoading = ref<boolean>(false);
const isSaving = ref<boolean>(false);
const errors = ref(null);
const router = useRouter();
const item = ref<Article|null>(null);

const props = defineProps({
  id: {
    required: true,
    type: String,
  },
});

const loadData = async () => {
  try {
    isLoading.value = true;

    const { data } = await articleStore.loadById(props.id);
    item.value = data;
  } finally {
    isLoading.value = false;
  }
};

const handleSubmit = async (data: Article) => {
  try {
    isSaving.value = true;

    await articleStore.update(props.id, data);

    await router.push({ name: 'articles.show', params: { id: props.id } });
  } catch (e: any) {
    if (e.response?.status !== VALIDATION_ERROR) {
      throw e;
    }

    errors.value = e.response.data.errors;
  } finally {
    isSaving.value = false;
  }
};

loadData();
</script>
