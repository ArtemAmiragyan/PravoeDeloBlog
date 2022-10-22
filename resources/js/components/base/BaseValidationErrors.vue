<template>
  <div class="base-validation-errors">
    <div v-for="error in errors" class="base-validation-errors__item">
     <div v-for="subError in error" class="base-validation-errors__subitem">
       {{ subError }}
     </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
  errors: String
})
</script>
