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

const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + useStore().projectId)
        project.value = response.data
    } catch (error) {
        console.log(error)
    }
}
const project = ref(null)
getProject();

const getStatus = async () => {
    try {
        const response = await axios.get('/api/projects_status')
        statuses.value = response.data
    } catch (error) {
        console.log(error)
    }
}
const statuses = ref(null)
getStatus();
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
                    <div v-for="status in statuses"
                         v-bind:class=" project.status_code === status.code ? 'badge badge-outline':'badge badge-outline badge-neutral'">
                        {{ status.label }}
                    </div>
                </div>
            </div>

        </Box>
    </AuthenticatedLayout>
</template>
