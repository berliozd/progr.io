<script setup>
defineProps({
  title: '',
  open: false
})
</script>

<template>
  <details class="collapse collapse-arrow bg-neutral/70 border rounded-lg shadow-secondary-content/50 shadow-lg text-white/70 mt-2" :open="open">
    <summary class="collapse-title text-xl mb-2 font-medium">{{ title }}</summary>
    <div class="collapse-content">
      <slot/>
    </div>
  </details>
</template>
