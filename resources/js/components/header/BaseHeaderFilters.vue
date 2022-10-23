<template>
  <div class="header-filters">
    <router-link class="header-filters__item" :to="{ name: 'home' }">
      All Posts
    </router-link>

    <router-link class="header-filters__item" :to="{ name: 'my-articles' }">
      My Posts
    </router-link>
  </div>
</template>

<script setup lang="ts">
</script>
