<script setup>
import {trans} from "laravel-vue-i18n";

const props = defineProps({'competitor': null})
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:space-x-4 ">
        <div class="flex flex-col sm:w-2/5">
            <div>{{ trans('app.project.competitor.name') }} : {{ competitor.name }}</div>
            <div>{{ trans('app.project.competitor.url') }} : <a :href="competitor.url" target="_blank"
                                                                class="text-xs underline">{{ competitor.url }}</a></div>
        </div>
        <div class="flex flex-col sm:w-3/5 h-full">
            <div class="font-bold">{{ trans('app.project.competitor.description') }} :</div>
            <div class="">{{ competitor.description }}</div>
        </div>
    </div>
</template>
