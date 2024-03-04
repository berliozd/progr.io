<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
  hideAfterDelay: Boolean,
  error: String
});
onMounted(() => {
  if (!props.hideAfterDelay) {
    return;
  }
  setTimeout(
      () => hidden.value = 'opacity-0',
      10000
  );
})

const hidden = ref('opacity-100');

</script>

<template>
  <div role="alert" class="alert alert-error transition duration-700 ease-out" :class="hidden">
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ error }}</span>
  </div>
</template>
