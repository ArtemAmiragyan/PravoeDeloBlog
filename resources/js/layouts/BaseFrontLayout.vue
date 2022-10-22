<template>
  <div>
    <BaseHeader/>

    <slot/>
  </div>
</template>

<script setup lang="ts">
import BaseHeader from '@/components/header/BaseHeader.vue';
</script>
