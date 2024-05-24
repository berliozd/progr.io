<script setup>
import {capitalize} from "vue";

import Ad from "@/Pages/Catalog/Partials/Ad.vue";
import NoteLogo from "@/Pages/App/Partials/NoteLogo.vue";
import CreateAccountBlock from "@/Pages/Partials/CreateAccountBlock.vue";

const props = defineProps({'project': Object})
</script>

<template>
    <Ad :el="'top'"/>
    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <h2>{{ project.title }}</h2>
    </div>
    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ project.description }}</div>
    </div>
    <CreateAccountBlock/>
    <template v-for="note in project.notes" class="" :key="note.id">
        <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70">
            <h3 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ capitalize(note.type.label) }}:</h3>
            <div class="flex flex-row">
                <NoteLogo :note="note"/>
                <pre class="text-wrap font-sans p-2">{{ note.content }}</pre>
            </div>
        </div>
    </template>
    <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70"
         v-if="project.competitors && project.competitors.length">
        <h3 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ $t('app.project.competitors') }}:</h3>
        <template v-for="competitor in project.competitors" class="" :key="competitor.id">
            <div class="flex flex-col m-2 rounded-lg border shadow-2xl bg-neutral/70">
                <h4 class="bg-white/30 rounded-t-lg p-2 text-xl">{{ capitalize(competitor.name) }}:</h4>
                <div class="">
                    <div class="m-4">{{ competitor.description }}</div>
                    <div class="m-4">
                        <a :href="competitor.url" target="_blank" class="underline">{{ competitor.url }}</a>
                    </div>
                    <template v-for="competitorNote in competitor.notes" class="" :key="competitorNote.id">
                        <div class="flex flex-col rounded-b-lg border-t shadow-2xl bg-neutral/70">
                            <h5 class="bg-white/10 p-2 text-xl">{{ capitalize(competitorNote.type.label) }}:</h5>
                            <div class="flex flex-row ">
                                <NoteLogo :note="competitorNote"/>
                                <pre class="text-wrap font-sans p-2 rounded-b-lg">{{ competitorNote.content }}</pre>
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
