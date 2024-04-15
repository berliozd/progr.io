<script setup>
import Badges from "@/Components/Badges.vue";

const props = defineProps({project: null, allStatuses: null})

const selectStatus = (badge) => {
  props.project.status = badge.id;
}
</script>

<template>
  <label class="block mb-2">{{ $t('app.project.status') }}:</label>
  <Badges :badges="allStatuses" :badge="project.status" @click="selectStatus($event)"/>
</template>
