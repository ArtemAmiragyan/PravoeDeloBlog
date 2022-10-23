<template>
  <form @submit.prevent="handleSubmit" class="base-article-comment-form">
    <textarea :disabled="isSaving" v-model="comment" class="base-article-comment-form__textarea" />
    <button :disabled="isSaving" class="base-article-comment-form__submit" type="submit">
      Send
    </button>
    <BaseValidationErrors :errors="errors"/>
  </form>
</template>

<script setup lang="ts">
import { defineProps, ref } from 'vue';
import { useArticleStore } from '@/stores/article';
import { useAuthStore } from '@/stores/auth';
import moment from 'moment';
import BaseValidationErrors from '@/components/base/BaseValidationErrors.vue';
import { VALIDATION_ERROR } from '@/constants/response-codes';

const props = defineProps({
  articleId: {
    required: true,
    type: Number,
  },
});

const emit = defineEmits(['comment:send']);

const articleStore = useArticleStore();
const auth = useAuthStore();
const comment = ref<string>('');
const isSaving = ref<boolean>(false);
const errors = ref(null);

const handleSubmit = async () => {
  try {
    isSaving.value = true;

    await articleStore.saveComment({ content: comment.value, article_id: props.articleId });

    const date = moment().format('MMM D, yyyy');

    emit('comment:send', { content: comment.value, author_name: auth.user.name, date });
    comment.value = '';
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
