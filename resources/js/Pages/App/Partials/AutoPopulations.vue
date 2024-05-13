<script setup>
import Badges from "@/Components/Badges.vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({project: null, allAutoPopulations: null})

const selectAutoPopulation = (badge) => {
    props.project.auto_population = badge.id;
}
</script>

<template>
    <label class="mb-2 flex  justify-start space-x-1 items-center">
        {{ $t('app.project.auto_populations.title') }} :
    </label>
    <Badges :badges="allAutoPopulations" :badge="project.auto_population"
            @click="selectAutoPopulation($event)"/>
    <div class="alert alert-warning">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-info">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 16v-4"/>
            <path d="M12 8h.01"/>
        </svg>
        {{ $t('app.project.auto_populations.description', {nb_credits: usePage().props.app.auto_population_credits}) }}
    </div>
</template>
