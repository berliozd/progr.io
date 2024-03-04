<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {ref} from "vue";
import {useStore} from "@/Composables/store.js";

if (!useStore().projectId) {
    router.visit(route('app.projects'));
}

const getData = async () => {
    try {
        const response = await axios.get('/api/projects/' + useStore().projectId)
        project.value = response.data
    } catch (error) {
        console.log(error)
    }
}
const project = ref(null)
getData()

</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Project')"/>
        </template>
        <Box>
            Project
            {{ project }}
        </Box>
    </AuthenticatedLayout>
</template>
