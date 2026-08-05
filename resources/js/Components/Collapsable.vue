<script setup>
defineProps({
  title: '',
  open: false
})
</script>

<template>
  <details class="collapse collapse-arrow bg-base-200 border border-base-300 rounded-box shadow-soft text-base-content mt-2" :open="open">
    <summary class="collapse-title text-lg mb-2 font-semibold">{{ title }}</summary>
    <div class="collapse-content">
      <slot/>
    </div>
  </details>
</template>
