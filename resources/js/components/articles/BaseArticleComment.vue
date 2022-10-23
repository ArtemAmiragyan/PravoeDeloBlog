<template>
  <div class="base-article-comment">
    <div class="base-article-comment__author">{{ item.date }}</div>
    <div class="base-article-comment__author">
      <b>{{ item.author_name }}</b>
    </div>
    <div class="base-article-comment__content">{{ item.content }}</div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, PropType } from 'vue';
import Comment from '@/interfaces/article/Comment';

const props = defineProps({
  item: {
    type: Object as PropType<Comment>,
    required: true,
  },
});
</script>
