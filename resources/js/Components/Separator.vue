<script setup>
defineProps({
  text: String
})
</script>
<template>
  <div class="flex items-center">
    <div class="flex-1 h-px bg-neutral-content"></div>
    <div class="px-4 text-neutral-content">{{ text }}</div>
    <div class="flex-1 h-px bg-neutral-content"></div>
  </div>
</template>

