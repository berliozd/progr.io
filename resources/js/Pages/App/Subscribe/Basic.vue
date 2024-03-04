<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";

import {Head} from '@inertiajs/vue3';
import Box from "@/Components/Box.vue";
</script>
<template>
    <Head v-bind:title="$t('Basic')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Basic')"/>
        </template>
        <Box>
          {{$t('homepage.welcome')}}
        </Box>
    </AuthenticatedLayout>
</template>
