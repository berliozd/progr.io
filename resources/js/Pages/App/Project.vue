<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {ref} from "vue";
import {useStore} from "@/Composables/store.js";
import getStatuses from "@/Composables/getStatuses.js";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";
import StatusBadges from "@/Pages/App/Partials/StatusBadges.vue";

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
getStatuses().then((response) => {
    statuses.value = response
})

const saveProject = async () => {
    try {
        await axios.patch('/api/projects/' + useStore().projectId, project.value);
        useStore().setToast('Saved!', 3000);
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

const selectProjectStatus = (status) => {
    project.value.status = status.id;
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
                <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status"
                              v-bind:on-click="selectProjectStatus"/>
            </div>

            <SaveProjectButton v-bind:on-click="saveProject"></SaveProjectButton>
        </Box>
    </AuthenticatedLayout>
</template>
