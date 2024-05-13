<script setup>
import Badges from "@/Components/Badges.vue";

const props = defineProps({project: null, allVisibilities: null})

const selectVisibility = (badge) => {
    props.project.visibility = badge.id;
}
</script>

<template>
    <label class="block mb-2">{{ $t('app.project.visibility') }}:</label>
    <Badges :badges="allVisibilities" :badge="project.visibility"
            @click="selectVisibility($event)"/>
</template>
