<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
  },
  title: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <button
      :type="type"
      class="inline-flex items-center justify-center gap-2 rounded-btn px-4 py-2
        bg-base-200
        text-sm font-semibold text-base-content
        border border-base-300
        hover:bg-base-300
        transition-colors duration-150
        focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-base-100
        disabled:opacity-50 disabled:pointer-events-none"
      :title="title"
  >
    <slot/>
  </button>
</template>
