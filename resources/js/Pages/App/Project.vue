<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {ref} from "vue";
import {useStore} from "@/Composables/store.js";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";

if (!useStore().projectId) {
    router.visit(route('app.projects'));
}

const project = ref(null)
const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + useStore().projectId)
        project.value = response.data
    } catch (error) {
        console.log(error)
    }
}
getProject();

const statuses = ref(null)
const getStatus = async () => {
    try {
        const response = await axios.get('/api/projects_status')
        statuses.value = response.data
    } catch (error) {
        console.log(error)
    }
}
getStatus();

const save = async () => {
    try {
        await axios.patch('/api/projects/' + useStore().projectId, project.value);
        useStore().setToast('Saved!', 3000);
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

const statusBadge = (status) => {
    const baseCss = 'badge badge-outline';
    if (project.value.status_code === status.code) {
        return baseCss + ' dark:bg-gray-200 dark:text-gray-800 bg-white text-gray-800';
    }
    return baseCss + ' hover:cursor-pointer';
}

const selectProjectStatus = (status) => {
    project.value.status = status.id;
    project.value.status_code = status.code;
}
</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Project')"/>
        </template>
        <Box class="space-y-4 relative" v-if="project">
            <div>
                <label for="title">{{ $t('app.project.title') }}:</label>
                <div class="mt-2">
                    <text-input v-model="project.title" name="title" class="w-full"></text-input>
                </div>
            </div>

            <div>
                <label for="description">{{ $t('app.project.description') }}:</label>
                <div class="mt-2">
                    <text-area v-model="project.description" rows="8" class="w-full"></text-area>
                </div>
            </div>

            <div>
                <label for="description" class="block">{{ $t('app.project.status') }}:</label>
                <div class="mt-2 flex flex-row space-x-2">
                    <div v-for="status in statuses" @click="selectProjectStatus(status)"
                         v-bind:class="statusBadge(status)">
                        {{ status.label }}
                    </div>
                </div>
            </div>

            <div class="flex w-full rounded border p-2 hover:cursor-pointer" @click="save()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-save mx-auto ">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
            </div>

        </Box>
    </AuthenticatedLayout>
</template>
