<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block mb-1 text-sm font-medium text-base-content/80">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
