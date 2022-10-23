<template>
  <BaseLoader v-if="isLoading"/>

  <div v-else class="article-page">
    <router-link class="article-show-page__create" :to="{ name: 'articles.create' }">
      <button>
        Create New Article
      </button>
    </router-link>

    <BaseArticleFilters v-model="filters" @filters:submit="filterItems" :categories="categories"/>

    <h2>Posts:</h2>
    <BaseLoader v-if="isRefreshing"/>
    <div v-else class="article-page__content">
      <BaseArticle v-for="(item, key) in items?.data" :key="key" :item="item"
                   @article:remove="loadData"/>
      <div v-if="isNoItemsFound">No items found</div>
      <BasePagination :data="items?.meta" @pagination:prev="filterItems"
                      @pagination:next="filterItems"/>
    </div>
  </div>
</template>

<script setup lang="ts">
import BaseArticleFilters from '@/components/articles/BaseArticleFilters.vue';
import BaseArticle from '@/components/articles/BaseArticle.vue';
import { useArticleStore } from '@/stores/article';
import { computed, ref } from 'vue';
import ArticleFilters from '@/interfaces/article/ArticleFilters';
import Article from '@/interfaces/article/Article';
import BaseLoader from '@/components/base/BaseLoader.vue';
import BasePagination from '@/components/base/BasePagination.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const articleStore = useArticleStore();
const isLoading = ref<boolean>(false);
const isRefreshing = ref<boolean>(false);
const filters = ref<ArticleFilters>({
  query: '',
  categories: [],
  date_from: '',
  date_to: '',
});
// @ts-ignore
const items = ref<{ data: Article[], meta: {}, link: {} } | null>(null);
const categories = ref([]);

const loadData = async () => {
  try {
    isLoading.value = true;

    // @ts-ignore
    const { data } = await articleStore.load({ user_id: auth.user.id });

    items.value = data;

    const categoriesResponse = await articleStore.loadCategories();
    categories.value = categoriesResponse.data;
  } finally {
    isLoading.value = false;
  }
};

const filterItems = async (page?: number) => {
  try {
    isRefreshing.value = true;

    const { data } = await articleStore.load({
      ...filters.value,
      page,
      // @ts-ignore
      user_id: auth.user.id,
    });

    items.value = data;
  } finally {
    isRefreshing.value = false;
  }
};

const isNoItemsFound = computed(() => {
  return !items.value?.data || !items.value.data.length;
});

loadData();
</script>
