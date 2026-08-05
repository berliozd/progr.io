<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, usePage} from '@inertiajs/vue3';
import Prices from "@/Components/Prices.vue";

defineProps({
    invoices: Array,
});

const nbCredits = usePage().props.auth.user.nb_credits
const usedCredits = usePage().props.auth.user.used_credits
const goTo = (url) => {
    window.location.href = url
}

</script>

<template>
    <Head v-bind:title="$t('Dashboard')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Dashboard')"/>
        </template>

        <Box>
            <div class="my-2 flex flex-col sm:flex-row gap-4">
                <div class="flex-1 rounded-btn bg-base-100 border border-base-300 p-4">
                    <p class="text-sm text-base-content/60">Credits used</p>
                    <p class="text-2xl font-bold">{{ usedCredits }}</p>
                </div>
                <div class="flex-1 rounded-btn bg-primary/10 border border-primary/20 p-4">
                    <p class="text-sm text-primary/80">Credits remaining</p>
                    <p class="text-2xl font-bold text-primary">{{ nbCredits }}</p>
                </div>
            </div>
            <div class="my-4 text-accent text-center border border-accent/40 bg-accent/5 rounded-btn p-3 font-medium">
                Let's buy more credits.
            </div>
            <Prices buy="true"/>
        </Box>

    </AuthenticatedLayout>
</template>
