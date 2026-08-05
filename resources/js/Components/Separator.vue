<script setup>
defineProps({
  text: String
})
</script>
<template>
  <div class="flex items-center">
    <div class="flex-1 h-px bg-base-300"></div>
    <div class="px-4 text-sm text-base-content/50">{{ text }}</div>
    <div class="flex-1 h-px bg-base-300"></div>
  </div>
</template>
