<template>
  <div>
    <BaseHeader/>

    <div class="container">
      <slot/>
    </div>
  </div>
</template>

<script setup lang="ts">
import BaseHeader from '@/components/header/BaseHeader.vue';
</script>
