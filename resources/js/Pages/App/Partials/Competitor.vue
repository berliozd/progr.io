<script setup>
import {trans} from "laravel-vue-i18n";

const props = defineProps({'competitor': null})
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:space-x-4 ">
        <div class="flex flex-col sm:w-2/5">
            <div><span class="font-medium">{{ trans('app.project.competitor.name') }}</span> : {{ competitor.name }}</div>
            <div><span class="font-medium">{{ trans('app.project.competitor.url') }}</span> : <a :href="competitor.url" target="_blank"
                                                                class="text-xs underline text-primary hover:text-primary/80">{{ competitor.url }}</a></div>
        </div>
        <div class="flex flex-col sm:w-3/5 h-full">
            <div class="font-semibold">{{ trans('app.project.competitor.description') }} :</div>
            <div class="text-base-content/80">{{ competitor.description }}</div>
        </div>
    </div>
</template>
