<script setup>
const props = defineProps({
  onClick: null,
  label: null,
  selected: null
})

const badgeCss = (selected) => {
  const baseCss = 'badge badge-lg text-nowrap transition-colors duration-150';
  if (selected) {
    return baseCss + ' badge-primary border-primary';
  }
  return baseCss + ' badge-outline border-base-300 hover:border-primary hover:text-primary hover:cursor-pointer';
}
</script>
<template>
  <div @click="onClick()" v-bind:class="badgeCss(selected)">
    {{ label }}
  </div>
</template>
