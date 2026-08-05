<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";
import Visibilities from "@/Pages/App/Partials/Visibilities.vue";
import Statuses from "@/Pages/App/Partials/Statuses.vue";
import AutoPopulations from "@/Pages/App/Partials/AutoPopulations.vue";

import {Head, router, usePage} from '@inertiajs/vue3';
import axios from "axios";
import {reactive, ref} from "vue";
import {trans} from "laravel-vue-i18n";
import {useStore} from "@/Composables/store.js";
import statuses from "@/Composables/statuses.js";
import visibilities from "@/Composables/visibilities.js";
import autoPopulations from "@/Composables/autoPopulations.js";
import Ad from "@/Pages/Catalog/Partials/AdMultiplex.vue";

const allStatuses = ref(null)
statuses().then((response) => {
    allStatuses.value = response
})
const allVisibilities = ref(null)
visibilities().then((response) => {
    allVisibilities.value = response
})

const allAutoPopulations = ref(null)
autoPopulations().then((response) => {
    allAutoPopulations.value = response
})

const project = reactive({
    title: '',
    description: '',
    status: 1,
    visibility: 1,
    auto_population: 1
})

const save = async () => {
    if (!validate()) {
        return;
    }
    try {
        await axios.post('/api/projects/', project).then((response) => {
            useStore().setToast('Created!');
            router.visit('/project/' + response.data.id);
        });
    } catch (error) {
        console.log(error)
    }
}

const validate = () => {
    usePage().props.error = ''
    if (project.title === '') {
        usePage().props.error = trans('app.project.title_error')
        return false;
    }
    if (project.description === '') {
        usePage().props.error = trans('app.project.description_error')
        return false;
    }
    if (!project.visibility) {
        usePage().props.error = trans('app.project.visibility_error')
        return false;
    }
    return true;
}
</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('New Project')"/>
        </template>
        <Box class="space-y-3 relative">
            <ErrorAlert v-bind:error="usePage().props.error" v-if="usePage().props.error"/>
            <InputLabel for="title" :value="$t('app.project.title') + ':'"/>
            <div class="flex flex-row">
                <TextInput v-model="project.title" name="title" class="w-full"></TextInput>
            </div>
            <InputLabel for="description" :value="$t('app.project.description') + ':'"/>
            <div class="flex flex-row">
                <TextArea v-model="project.description" rows="8" class="w-full"></TextArea>
            </div>
        </Box>
        <Box>
            <Statuses :project="project" :all-statuses="allStatuses"/>
            <hr class="border-base-300">
            <Visibilities :project="project" :all-visibilities="allVisibilities"/>
            <hr class="border-base-300">
            <autoPopulations :project="project" :all-auto-populations="allAutoPopulations"/>
        </Box>
        <SaveProjectButton v-bind:on-click="save"></SaveProjectButton>
        <Ad :el="'bottom'"/>
    </AuthenticatedLayout>
</template>
