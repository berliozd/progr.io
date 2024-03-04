<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head v-bind:title="trans('Premium')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Premium')"/>
        </template>
        <Box>Premium Premium Premium Premium Premium Premium Premium</Box>
    </AuthenticatedLayout>
</template>
