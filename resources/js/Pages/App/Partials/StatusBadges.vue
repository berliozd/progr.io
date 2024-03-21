<script setup>
const props = defineProps({
    onClick: null,
    statuses: null,
    projectStatus: null
})

const statusBadge = (status) => {
    const baseCss = 'badge badge-outline badge-lg mr-2 mb-2';
    if (props.projectStatus === status.id) {
        return baseCss + ' badge-accent';
    }
    return baseCss + ' hover:cursor-pointer';
}
</script>
<template>
    <div class="flex flex-wrap">
        <div v-for="status in statuses" @click="onClick(status)"
             v-bind:class="statusBadge(status)">
            {{ status.label }}
        </div>
    </div>
</template>
