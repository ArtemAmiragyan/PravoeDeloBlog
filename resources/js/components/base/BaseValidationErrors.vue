<template>
  <div class="base-validation-errors">
    <div v-for="(error, index) in errors" :key="index" class="base-validation-errors__item">
     <div v-for="(subError, index) in error" :key="index" class="base-validation-errors__subitem">
       {{ subError }}
     </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
  errors: Object,
});
</script>
