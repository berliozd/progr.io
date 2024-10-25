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
            <div class="my-2">
                <ul>
                    <li>
                        You have used {{ usedCredits }} credits.
                    </li>
                    <li>
                        You still have {{ nbCredits }} credits.
                    </li>
                </ul>
            </div>
            <div class="my-4 text-accent text-center border border-accent rounded-lg p-2">
                Let's buy more credits.
            </div>
            <Prices buy="true"/>
        </Box>

    </AuthenticatedLayout>
</template>

