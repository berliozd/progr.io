<script setup>
import Badges from "@/Components/Badges.vue";
import {trans} from "laravel-vue-i18n";

const props = defineProps({project: null, allVisibilities: null})
const addTrad = () => {
  props.allVisibilities?.forEach(visibility => {
    if (!visibility.label) {
      visibility.label = trans('app.project.visibilities.' + visibility.code)
    }
  })
  return props.allVisibilities
}
const selectVisibility = (badge) => {
  props.project.visibility = badge.id;
}
</script>

<template>
  <label class="block mb-2">{{ $t('app.project.visibility') }}:</label>
  <Badges :badges="addTrad()" :badge="project.visibility"
          @click="selectVisibility($event)"/>
</template>
