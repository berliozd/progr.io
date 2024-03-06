<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import Loader from "@/Components/Loader.vue";
import DeleteProject from "@/Pages/App/Partials/DeleteProject.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {computed, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {truncate} from 'lodash';
import AddProjectButton from "@/Pages/App/Partials/AddProjectButton.vue";

const getData = async () => {
    try {
        const response = await axios.get('/api/projects/')
        projects.value = response.data
        loaded.value = true
    } catch (error) {
        console.log(error)
    }
}
const projects = ref(null)
const loaded = ref(false);
const hasProject = computed(() => {
    if (projects.value) {
        return projects.value.length > 0
    }
    return false;
});
getData()
const navToProject = (project) => {
    useStore().setProjectId(project.id);
    router.visit(route('app.projects.detail'));
}
</script>
<template>
    <Head v-bind:title="$t('Projects')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Projects')"/>
        </template>
        <Loader v-if="!loaded"></Loader>
        <Box v-else class="relative">
            <div v-if="!hasProject" class="my-4">{{ $t('app.no_projects') }}</div>
            <div v-else class="my-4">{{ $t('app.nb_projects', {'nb': projects.length}) }}</div>
            <AddProjectButton/>
            <div class="overflow-auto h-80 my-2">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-gray-800 dark:text-gray-400">{{ $t('app.project.title') }}</th>
                        <th class="text-gray-800 dark:text-gray-400">{{ $t('app.project.description') }}</th>
                        <th class="text-center text-gray-800 dark:text-gray-400">{{ $t('app.project.status') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="hover:cursor-pointer" v-for="project in projects">
                        <td @click="navToProject(project);">
                            {{ project.title }}
                        </td>
                        <td @click="navToProject(project);">
                            {{ truncate(project.description, {'length': 50}) }}
                        </td>
                        <td class="text-center" @click="navToProject(project);">
                            {{ project.status_label }}
                        </td>
                        <td>
                            <DeleteProject v-bind:project-id="project.id"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <AddProjectButton/>
        </Box>
    </AuthenticatedLayout>
</template>
