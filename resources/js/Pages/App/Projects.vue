<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import AddProjectButton from "@/Pages/App/Partials/AddProjectButton.vue";
import SimpleLink from "@/Components/SimpleLink.vue";
import Badge from "@/Components/Badge.vue";
import DeleteModal from "@/Components/DeleteModal.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {computed, ref} from "vue";
import {truncate} from 'lodash';
import {useStore} from "@/Composables/store.js";
import AILogo from "@/Components/AILogo.vue";
import Ad from "@/Pages/Catalog/Partials/AdMultiplex.vue";

const getData = async () => {
    try {
        const response = await axios.get('/api/projects/')
        projects.value = response.data
        useStore().setIsLoading(false)
    } catch (error) {
        console.log(error)
    }
}
const projects = ref(null)
const hasProject = computed(() => {
    if (projects.value) {
        return projects.value.length > 0
    }
    return false;
});
getData()
const navToProject = (project) => {
    router.visit(route('app.projects.detail', project.id));
}
useStore().setIsLoading(true)
</script>
<template>
    <Head v-bind:title="$t('Projects')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Projects')"/>
        </template>
        <Box v-if="!useStore().loading" class="relative">
            <div v-if="!hasProject" class="my-4 text-base-content/70">{{ $t('app.no_projects') }}</div>
            <div v-else class="my-4 text-base-content/70">{{ $t('app.nb_projects', {'nb': projects.length}) }}</div>
            <div class="flex flex-grow justify-between items-center gap-4">
                <SimpleLink v-bind:href="route('app.ideas')">
                    <div class="rounded-btn border border-base-300 p-2 hover:border-primary hover:bg-base-100 transition-colors duration-150">
                        {{ $t('app.out_of_ideas') }}
                        <div class="flex flex-row ml-10 space-x-2 text-xs items-center">
                            <span class="underline">{{ $t('app.project.ask_ai_help') }}</span>
                            <AILogo/>
                        </div>
                    </div>
                </SimpleLink>
                <AddProjectButton/>
            </div>
            <div v-if="!hasProject"
                 class="my-4 h-32 rounded-box p-4 flex justify-center items-center border border-dashed border-base-300 text-base-content/50 uppercase text-xl font-semibold">
                {{ $t('app.project.no_project') }}
            </div>
            <div class="overflow-auto h-80 my-2 rounded-box border border-base-300" v-else>
                <div class="grid grid-cols-6 w-full mb-2 bg-base-200 px-2 py-2 sticky top-0">
                    <div class="font-semibold col-span-3">{{ $t('app.project.title') }}</div>
                    <div class="flex justify-around font-semibold col-span-2">{{ $t('app.project.status') }}</div>
                    <div class=""></div>
                </div>
                <div class="grid grid-cols-6 w-full p-2 items-center
                    hover:cursor-pointer [&:nth-child(even)]:bg-base-200/50
                    hover:bg-primary/5 transition-colors duration-150" v-for="project in projects">
                    <div @click="navToProject(project);" class=" col-span-3">
                        {{ truncate(project.title, {'length': 75}) }}
                    </div>
                    <div class="flex justify-around  col-span-2" @click="navToProject(project);">
                        <Badge :label="project.status_label"></Badge>
                    </div>
                    <div class="flex justify-end ">
                        <DeleteModal :id="project.id" :api-url="'\/api/projects\/'" @deleted="getData"
                                     :question=" $t('app.project.deletion_confirmation_question')"
                                     :confirmation-button-text="$t('app.project.delete')"/>
                    </div>
                </div>
            </div>
        </Box>
        <Ad :el="'bottom'"/>
    </AuthenticatedLayout>
</template>
