<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {computed, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {truncate} from 'lodash';
import Loader from "@/Components/Loader.vue";

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
</script>
<template>
    <Head v-bind:title="$t('Projects')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Projects')"/>
        </template>
        <Loader v-if="!loaded"></Loader>
        <Box v-else class="relative">
            <div v-if="!hasProject">{{ $t('app.no_projects') }}</div>
            <table class="table">
                <thead>
                <tr>
                    <th class="text-gray-800 dark:text-gray-400">{{ $t('app.project.title') }}</th>
                    <th class="text-gray-800 dark:text-gray-400">{{ $t('app.project.description') }}</th>
                    <th class="text-center text-gray-800 dark:text-gray-400">{{ $t('app.project.status') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover:cursor-pointer" v-for="project in projects"
                    @click="useStore().setProjectId(project.id); router.visit(route('app.projects.detail'));">
                    <td>{{ project.title }}</td>
                    <td>{{ truncate(project.description, {'length': 50}) }}</td>
                    <td class="text-center">{{ project.status_label }}</td>
                </tr>
                </tbody>
            </table>
            <div class="flex w-full rounded border mt-4 p-2 hover:cursor-pointer " @click="router.visit(route('app.projects.new'))">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-plus mx-auto ">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
            </div>
        </Box>
    </AuthenticatedLayout>
</template>
