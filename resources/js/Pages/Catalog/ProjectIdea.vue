<script setup>
import CatalogLayout from '@/Layouts/CatalogLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";

import {reactive, ref} from "vue";
import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {sortProjectChildren} from "@/Composables/App/useProject.js";
import ProjectPresentation from "@/Components/ProjectPresentation.vue";
import {trans} from "laravel-vue-i18n";

const project = reactive({title: '', description: '', status: ''})
const props = defineProps({'id': null})
const title = ref(null)

const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + props.id)
        Object.assign(project, response.data);
        sortProjectChildren(project)
        title.value = trans('Project Idea') + ' - ' + trans('app.ideas.catalog.category.' + project?.category?.code) + ' - ' + project?.title
    } catch
        (error) {
        console.log(error)
    }
}
getProject();
</script>
<template>
    <Head :title="title"/>
    <CatalogLayout>
        <template #header>
            <PageHeader :title="title"/>
        </template>
        <ProjectPresentation :project="project"/>
    </CatalogLayout>
</template>
