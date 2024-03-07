<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";

import {Head, router, usePage} from '@inertiajs/vue3';
import axios from "axios";
import {reactive, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";
import getStatuses from "@/Composables/getStatuses.js";
import StatusBadges from "@/Pages/App/Partials/StatusBadges.vue";
import AskAiField from "@/Components/AskAiField.vue";

const statuses = ref(null)
getStatuses().then((response) => {
    statuses.value = response
})

const project = reactive({
    title: {type: String, value: ''},
    description: {type: String, value: ''},
    status: {type: Number, value: 1},
})

const save = async () => {
    if (!validate()) {
        return;
    }
    if (project.title.value === '') {
        usePage().props.error = trans('app.project.title_error')
    }
    try {
        await axios.post('/api/projects/', project);
        useStore().setToast('Created!', 3000);
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

const validate = () => {
    usePage().props.error = ''
    if (project.title.value === '') {
        usePage().props.error = trans('app.project.title_error')
        return false;
    }
    if (project.description.value === '') {
        usePage().props.error = trans('app.project.description_error')
        return false;
    }
    return true;
}

const statusBadge = (status) => {
    const baseCss = 'badge badge-outline';
    if (project.status.value === status.id) {
        return baseCss + ' dark:bg-gray-200 dark:text-gray-800 bg-white text-gray-800';
    }
    return baseCss + ' hover:cursor-pointer';
}

const selectProjectStatus = (status) => {
    console.log(project.status);
    project.status.value = status.id;
    console.log(project.status);
}

</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('New Project')"/>
        </template>
        <Box class="space-y-4 relative">
            <ErrorAlert v-bind:error="usePage().props.error" v-if="usePage().props.error"/>
            <div>
                <label for="title">{{ $t('app.project.title') }}:</label>
                <div class="mt-2">
                    <text-input v-model="project.title.value" name="title" class="w-full"></text-input>
                </div>
            </div>

            <div>
                <label for="description">{{ $t('app.project.description') }}:</label>
                <div class="mt-2">
                    <text-area v-model="project.description.value" rows="8" class="w-full"></text-area>
                </div>
            </div>

            <div>
                <label for="description" class="block">{{ $t('app.project.status') }}:</label>
                <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status.value"
                              v-bind:on-click="selectProjectStatus"></StatusBadges>
            </div>

            <AskAiField v-bind:title="project.title" v-bind:description="project.description"
                        question-type="benefits"></AskAiField>
            <AskAiField v-bind:title="project.title" v-bind:description="project.description"
                        question-type="money"></AskAiField>

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
