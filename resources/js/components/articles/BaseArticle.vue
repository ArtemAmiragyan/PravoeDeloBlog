<template>
  <div class="base-article" :class="isDetailsPage ? 'base-article_no-bordered' : ''">
    <div class="base-article__date">
      {{ item.date }}
    </div>
    <h1 class="base-article__title">
      <template v-if="isDetailsPage">
        {{ item.title }}
      </template>
      <router-link v-else :to="{ name: 'articles.show', params: { id: item.id } }">
        {{ item.title }}
      </router-link>
    </h1>
    <div class="base-article__author">
      <b>Author: {{ item.author_name }}</b>
    </div>
    <div class="base-article__content">
      {{ item.content }}
    </div>
    <div v-if="!isDetailsPage && item.comments_count" class="base-article__comments_count">
      <b>Comments count: {{ item.comments_count }}</b>
    </div>
    <div class="base-article__actions">
      <div v-if="item.can?.update" class="base-article__edit">
        <router-link :disabled="isDeleting" :to="{ name: 'articles.edit', params: { id: item.id } }">
          <button>Edit</button>
        </router-link>
      </div>
      <div v-if="item.can?.delete" class="base-article__delete">
        <button :disabled="isDeleting" @click="handleDelete">Delete</button>
      </div>
    </div>
    <div class="base-article__category"
         :class="isDetailsPage ? 'base-article__category_left' : ''">
      <b>Category: {{ item.category }}</b>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, PropType, ref } from 'vue';
import Article from '@/interfaces/article/Article';
import { useArticleStore } from '@/stores/article';

const props = defineProps({
  item: {
    type: Object as PropType<Article>,
    required: true,
  },
  isDetailsPage: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const emit = defineEmits(['article:remove']);

const articleStore = useArticleStore();
const isDeleting = ref<boolean>(false);

const handleDelete = async () => {
  try {
    if (!confirm('Are you sure you want to delete this item?')) {
      return;
    }

    isDeleting.value = true;

    // @ts-ignore
    await articleStore.remove(props.item.id);

    emit('article:remove');
  } finally {
    isDeleting.value = false;
  }
};
</script>
