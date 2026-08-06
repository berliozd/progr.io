<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import AddProjectButton from "@/Pages/App/Partials/AddProjectButton.vue";
import SimpleLink from "@/Components/SimpleLink.vue";
import Badge from "@/Components/Badge.vue";

import {Head, router, usePage} from '@inertiajs/vue3';
import {computed} from "vue";
import {truncate} from 'lodash';
import Prices from "@/Components/Prices.vue";

const props = defineProps({
    invoices: Array,
    projects: Array,
    projectsCount: Number,
});

const nbCredits = usePage().props.auth.user.nb_credits
const usedCredits = usePage().props.auth.user.used_credits
const goTo = (url) => {
    window.location.href = url
}

const hasProject = computed(() => props.projectsCount > 0);
const navToProject = (project) => {
    router.visit(route('app.projects.detail', project.id));
}

</script>

<template>
    <Head v-bind:title="$t('Dashboard')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Dashboard')"/>
        </template>

        <Box>
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold">{{ $t('Projects') }}</h2>
                <AddProjectButton/>
            </div>
            <div v-if="!hasProject"
                 class="my-4 h-32 rounded-box p-4 flex justify-center items-center border border-dashed border-base-300 text-base-content/50 uppercase text-xl font-semibold">
                {{ $t('app.project.no_project') }}
            </div>
            <template v-else>
                <div class="my-2 text-base-content/70">{{ $t('app.nb_projects', {'nb': projectsCount}) }}</div>
                <div class="my-2 rounded-box border border-base-300 divide-y divide-base-300">
                    <div class="grid grid-cols-6 w-full p-2 items-center
                        hover:cursor-pointer [&:nth-child(even)]:bg-base-200/50
                        hover:bg-primary/5 transition-colors duration-150" v-for="project in projects"
                         :key="project.id" @click="navToProject(project)">
                        <div class="col-span-4">{{ truncate(project.title, {'length': 75}) }}</div>
                        <div class="flex justify-end col-span-2">
                            <Badge :label="project.status_label"></Badge>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end my-2">
                    <SimpleLink v-bind:href="route('app.projects')">{{ $t('Projects') }}</SimpleLink>
                </div>
            </template>
        </Box>

        <Box class="mt-4">
            <div class="my-2 flex flex-col sm:flex-row gap-4">
                <div class="flex-1 rounded-btn bg-base-100 border border-base-300 p-4">
                    <p class="text-sm text-base-content/60">Credits used</p>
                    <p class="text-2xl font-bold">{{ usedCredits }}</p>
                </div>
                <div class="flex-1 rounded-btn bg-primary/10 border border-primary/20 p-4">
                    <p class="text-sm text-primary/80">Credits remaining</p>
                    <p class="text-2xl font-bold text-primary">{{ nbCredits }}</p>
                </div>
            </div>
            <div class="my-4 text-accent text-center border border-accent/40 bg-accent/5 rounded-btn p-3 font-medium">
                Let's buy more credits — one-time payment, no subscription.
            </div>
            <Prices buy="true"/>
        </Box>

    </AuthenticatedLayout>
</template>
