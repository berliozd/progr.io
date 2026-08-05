<script setup>
import {onMounted, ref} from 'vue';

const model = defineModel({
  type: String,
  required: true,
});

defineProps({
  maxLength: null
})
const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
  <input
      class="input input-bordered w-full bg-base-100 border-base-300 text-base-content
      focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/40"
      v-model="model"
      ref="input"
      :maxlength="maxLength"
  />
</template>
