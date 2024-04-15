<script setup>
const props = defineProps({
  onClick: null,
  badges: null,
  badge: null
})

const emits = defineEmits(['click'])

const badgeCss = (badge) => {
  const baseCss = 'badge badge-outline badge-lg mr-2 mb-2 ';
  if (props.badge === badge.id) {
    return baseCss + ' badge-accent';
  }
  return baseCss + ' hover:cursor-pointer hover:text-neutral hover:bg-neutral-content';
}
</script>
<template>
  <div class="flex flex-wrap">
    <div v-for="badge in badges" @click="emits('click',badge)" :class="badgeCss(badge)">
      {{ badge.label }}
    </div>
  </div>
</template>
