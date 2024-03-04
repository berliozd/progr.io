<script setup>
import {onMounted, ref} from "vue";

let props = defineProps({
  content: String,
  error: Boolean,
})
const hidden = ref('opacity-100');
const hideAfterDelay = (e) => {
  setTimeout(
      () => hidden.value = 'opacity-0',
      props.error ? 3000 : 1000
  )
};

onMounted(() => {
  hideAfterDelay();
})

</script>
<template>
  <div class="toast toast-bottom toast-start transition duration-700 ease-out z-50"
       :class="hidden">
    <div class="alert alert-error" v-if="error === true">
      <span>{{ content }}</span>
    </div>
    <div class="alert alert-info" v-else>
      <span>{{ content }}</span>
    </div>
  </div>
</template>
