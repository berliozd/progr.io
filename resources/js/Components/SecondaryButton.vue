<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
  },
});
</script>

<template>
  <button
      :type="type"
      class="inline-flex items-center px-4 py-2
        bg-neutral
        hover:bg-base-100
        rounded-md font-semibold text-xs
        uppercase tracking-widest shadow-sm
        focus:outline-none
        focus:ring-2 focus:ring-secondary
        disabled:opacity-25 transition ease-in-out duration-150"
  >
    <slot/>
  </button>
</template>
