<template>
  <div class="base-pagination">
    <button v-if="prev" @click="handlePrev">
      Prev
    </button>
    <button v-if="data.last_page > 1" @click="handleNext">
      Next
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed, defineProps } from 'vue';

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['pagination:next', 'pagination:prev']);

const prev = computed(() => {
  // @ts-ignore
  return props.data.current_page - 1;
});

const next = computed(() => {
  // @ts-ignore
  return props.data.current_page + 1;
});

const handlePrev = () => {
  emit('pagination:prev', prev.value);
};

const handleNext = () => {
  emit('pagination:next', next.value);
};
</script>
