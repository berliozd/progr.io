<script setup>

import {capitalize} from "vue";
import NoteLogo from "@/Pages/App/Partials/NoteLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, usePage} from "@inertiajs/vue3";
import {trans} from "laravel-vue-i18n";

const props = defineProps({'project': Object})
</script>

<template>
    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <h2>{{ project.title }}</h2>
    </div>

    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ project.description }}</div>
    </div>

    <div class="flex flex-col sm:flex-row rounded-lg border p-4 shadow-2xl bg-primary/30 text-xl"
         v-if="!usePage().props.auth?.user">
        <div class="">
            <div class="text-2xl">Would you like to give it a try?</div>
            <div class="">
                How about creating your own project and experiencing the benefits of our
                AI-assisted tools for defining and refining your project ideas?
            </div>
        </div>
        <div class="p-2 flex justify-center">
            <PrimaryButton @click="router.visit(route('register'))">{{ trans('auth.create_account') }}</PrimaryButton>
        </div>
    </div>

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
</template>
