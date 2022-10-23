<template>
  <BaseLoader v-if="isLoading"/>
  <div v-else class="article-show-page">
    <BaseArticle :is-details-page="true" :item="item" @article:remove="handleRemove"/>

    <div class="article-show-page__comments">
      <h2>Comments:</h2>
      <BaseArticleCommentForm :articleId="item?.id" @comment:send="addComment"/>
      <BaseArticleComment v-for="comment in item?.comments" :key="comment.id" :item="comment"/>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, ref } from 'vue';
import { useArticleStore } from '@/stores/article';
import Article from '@/interfaces/article/Article';
import BaseArticle from '@/components/articles/BaseArticle.vue';
import BaseLoader from '@/components/base/BaseLoader.vue';
import BaseArticleComment from '@/components/articles/BaseArticleComment.vue';
import BaseArticleCommentForm from '@/components/articles/BaseArticleCommentForm.vue';
import Comment from '@/interfaces/article/Comment';
import { useRouter } from 'vue-router';

const props = defineProps({
  id: {
    required: true,
    type: String,
  },
});

const articleStore = useArticleStore();
const item = ref();
const isLoading = ref<boolean>(false);
const router = useRouter();

const loadData = async () => {
  try {
    isLoading.value = true;

    const { data } = await articleStore.loadById(props.id);

    item.value = data;
  } finally {
    isLoading.value = false;
  }
};

const addComment = (comment: Comment) => {
  item.value?.comments?.unshift(comment);
};

const handleRemove = () => {
  router.push({ name: 'my-articles' });
};

loadData();
</script>
