<template>
  <div class="base-article-filters">
    <h2>Filters:</h2>

    <div class="base-article-filters__main-section">
      <div class="base-article-filters__item">
        <label>Search</label>
        <input v-model="modelValue.query" type="text"/>
      </div>
      <div class="base-article-filters__item">
        <label>Categories</label>
        <vue-select v-model="modelValue.categories" :multiple="true" :close-on-select="true"
                    :options="categories" type="text"/>
      </div>
      <div class="base-article-filters__item">
        <label>Date From</label>
        <datepicker v-model="modelValue.date_from" auto-apply :enableTimePicker="false"/>
      </div>
      <div class="base-article-filters__item">
        <label>Date To</label>
        <datepicker v-model="modelValue.date_to" auto-apply :enableTimePicker="false"/>
      </div>
      <button @click="emit('filters:submit')" class="base-article-filters__submit">
        Submit
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, PropType } from 'vue';
import ArticleFilters from '@/interfaces/article/ArticleFilters';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
  modelValue: {
    type: Object as PropType<ArticleFilters>,
    required: true,
  },
  categories: Array,
});

const emit = defineEmits(['filters:submit']);
</script>
