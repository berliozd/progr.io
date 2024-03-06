<script setup>
const props = defineProps({
    onClick: null,
    statuses: null,
    projectStatus: null
})

const statusBadge = (status) => {
    const baseCss = 'badge badge-outline badge-lg';
    if (props.projectStatus === status.id) {
        return baseCss + ' dark:bg-gray-200 dark:text-gray-800 bg-white text-gray-800';
    }
    return baseCss + ' hover:cursor-pointer';
}
</script>
<template>
    <div class="mt-2 flex flex-row space-x-2">
        <div v-for="status in statuses" @click="onClick(status)"
             v-bind:class="statusBadge(status)">
            {{ status.label }}
        </div>
    </div>
</template>
