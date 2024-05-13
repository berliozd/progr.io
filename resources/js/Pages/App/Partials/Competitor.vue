<script setup>

const props = defineProps({'competitor': null})
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:space-x-4 ">
        <div class="flex flex-col sm:w-2/5">
            <div>Name : {{ competitor.name }}</div>
            <div>Url : <a :href="competitor.url" target="_blank"
                          class="text-xs underline">{{ competitor.url }}</a></div>
        </div>
        <div class="flex flex-col sm:w-3/5 h-full">
            <div>Description :</div>
            <div class="text-xs">{{ competitor.description }}</div>
        </div>
    </div>
</template>
