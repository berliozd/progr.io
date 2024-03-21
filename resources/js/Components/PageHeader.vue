<script setup>
import Back from "@/Components/Back.vue";

const model = defineProps({
    title: String
});
</script>

<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
        <h2 class="font-semibold text-xl leading-tight">{{ title }}</h2>
        <Back/>
    </div>
</template>
