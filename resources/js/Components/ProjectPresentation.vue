<script setup>
import {capitalize} from "vue";

import Ad from "@/Pages/Catalog/Partials/AdMultiplex.vue";
import NoteLogo from "@/Pages/App/Partials/NoteLogo.vue";
import CreateAccountBlock from "@/Pages/Partials/CreateAccountBlock.vue";

const props = defineProps({'project': Object})
</script>

<template>
    <Ad :el="'top'"/>
    <div class="flex flex-col card-surface p-4 text-2xl font-bold">
        <h2>{{ project.title }}</h2>
    </div>
    <div class="flex flex-col card-surface p-4 text-lg">
        <div>{{ project.description }}</div>
    </div>
    <CreateAccountBlock/>
    <template v-for="note in project.notes" class="" :key="note.id">
        <div class="flex flex-col card-surface overflow-hidden">
            <h3 class="bg-base-200 border-b border-base-300 p-3 text-xl font-semibold">{{ capitalize(note.type.label) }}:</h3>
            <div class="flex flex-row">
                <NoteLogo :note="note"/>
                <pre class="text-wrap font-sans p-3">{{ note.content }}</pre>
            </div>
        </div>
    </template>
    <div class="flex flex-col card-surface overflow-hidden"
         v-if="project.competitors && project.competitors.length">
        <h3 class="bg-base-200 border-b border-base-300 p-3 text-xl font-semibold">{{ $t('app.project.competitors') }}:</h3>
        <template v-for="competitor in project.competitors" class="" :key="competitor.id">
            <div class="flex flex-col m-3 card-surface overflow-hidden">
                <h4 class="bg-base-200 border-b border-base-300 p-2 text-lg font-semibold">{{ capitalize(competitor.name) }}:</h4>
                <div class="">
                    <div class="m-4">{{ competitor.description }}</div>
                    <div class="m-4">
                        <a :href="competitor.url" target="_blank" class="underline text-primary hover:text-primary/80">{{ competitor.url }}</a>
                    </div>
                    <template v-for="competitorNote in competitor.notes" class="" :key="competitorNote.id">
                        <div class="flex flex-col border-t border-base-300">
                            <h5 class="bg-base-200/60 p-2 text-lg font-medium">{{ capitalize(competitorNote.type.label) }}:</h5>
                            <div class="flex flex-row ">
                                <NoteLogo :note="competitorNote"/>
                                <pre class="text-wrap font-sans p-3">{{ competitorNote.content }}</pre>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
    <Ad :el="'bottom'"/>
    <CreateAccountBlock/>
</template>
