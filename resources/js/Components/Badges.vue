<script setup>
const props = defineProps({
  onClick: null,
  badges: null,
  badge: null
})

const emits = defineEmits(['click'])

const badgeCss = (badge) => {
  const baseCss = 'badge badge-lg mr-2 mb-2 transition-colors duration-150';
  if (props.badge === badge.id) {
    return baseCss + ' badge-primary border-primary';
  }
  return baseCss + ' badge-outline border-base-300 hover:cursor-pointer hover:border-primary hover:text-primary';
}
</script>
<template>
  <div class="flex flex-wrap">
    <div v-for="badge in badges" @click="emits('click',badge)" :class="badgeCss(badge)">
      {{ badge.label }}
    </div>
  </div>
</template>
