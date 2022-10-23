<template>
  <form @submit.prevent="handleSubmit" class="base-article-form">
    <div class="base-article-form__item">
      <label>Title</label>
      <input :disabled="isDisabled" v-model="form.title" class="base-article-form__input"/>
    </div>

    <div class="base-article-form__item">
      <label>Category</label>
      <input :disabled="isDisabled" v-model="form.category" class="base-article-form__input"/>
    </div>

    <div class="base-article-form__item">
      <label>Content</label>
      <textarea :disabled="isDisabled" v-model="form.content" class="base-article-form__textarea"/>
    </div>

    <button :disabled="isDisabled" class="base-article-form__submit" type="submit">
      Save
    </button>
  </form>
</template>

<script setup lang="ts">
import { defineProps, PropType, ref } from 'vue';
import Article from '@/interfaces/article/Article';

const props = defineProps({
  isDisabled: Boolean,
  article: {
    required: false,
    type: Object as PropType<Article>,
    default: null,
  },
});

const emit = defineEmits(['submit']);
const form = ref<Article>(getDefaultForm());

const handleSubmit = () => {
  emit('submit', form.value);
};

if (props.article) {
  (() => {
    form.value = props.article ? props.article : getDefaultForm()
  })();
}

function getDefaultForm() {
  return {
    title: '',
    content: '',
    category: '',
  };
}
</script>
