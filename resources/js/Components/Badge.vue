<script setup>
const props = defineProps({
  onClick: null,
  label: null,
  selected: null
})

const badgeCss = (selected) => {
  const baseCss = 'badge badge-outline badge-lg text-nowrap';
  if (selected) {
    return baseCss + ' badge-accent';
  }
  return baseCss + ' hover:cursor-pointer';
}
</script>
<template>
  <div @click="onClick()" v-bind:class="badgeCss(selected)">
    {{ label }}
  </div>
</template>
