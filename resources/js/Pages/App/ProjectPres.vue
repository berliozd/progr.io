<script setup>
import Box from "@/Components/Box.vue";
import PresentationLayout from "@/Layouts/PresentationLayout.vue";

import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {reactive, ref} from "vue";
import {sortProjectChildren} from "@/Composables/App/useProject.js";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ProjectPresentation from "@/Components/ProjectPresentation.vue";

const project = reactive({title: '', description: '', status: ''})
const projectFound = ref(true);
const props = defineProps({id: null})
const loaded = ref(false);

const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + props.id)
        projectFound.value = true
        Object.assign(project, response.data);
        await sortProjectChildren(project)
        setTimeout(() => {
            loaded.value = true
        }, 1000)
    } catch (error) {
        console.log(error)
        projectFound.value = false
    }
}

getProject();
</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <PresentationLayout>
        <Box class="space-y-2 bg-primary/80 relative" v-if="projectFound">

            <div class="flex flex-row justify-center">
                <ApplicationLogo/>
            </div>

            <ProjectPresentation :project="project"/>

        </Box>
        <Box class="space-y-2 bg-primary/80 relative" v-else>
            {{ $t('app.no_projects_found') }}
        </Box>
        <div id='presentation' v-if="loaded"></div>

    </PresentationLayout>
</template>
