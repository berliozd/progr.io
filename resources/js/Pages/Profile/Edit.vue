<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import PageHeader from "@/Components/PageHeader.vue";
import CancelSubscriptionForm from "@/Pages/Profile/Partials/CancelSubscriptionForm.vue";
import Box from "@/Components/Box.vue";

import {Head, usePage} from '@inertiajs/vue3';
import {trans} from "laravel-vue-i18n";
import {computed} from "vue"
import date from "@/Composables/date.js";
import SettingsForm from "@/Pages/Profile/Partials/SettingsForm.vue";

const subscription = computed(() => usePage().props.auth.subscription)

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head v-bind:title="trans('Profile')"/>

    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="trans('Profile')"/>
        </template>

        <Box>
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />
        </Box>

        <Box>
            <UpdatePasswordForm class="max-w-xl"/>
        </Box>

        <Box>
            <SettingsForm class="max-w-xl"/>
        </Box>

        <Box>
            <DeleteUserForm class="max-w-xl"/>
        </Box>

        <Box v-if="subscription">
            <div v-if="subscription.on_grace_period">
                {{ trans('You subscription will end on ') }} {{ date(subscription.end_date) }}
            </div>
            <template v-else>
                <CancelSubscriptionForm class="max-w-xl"/>
            </template>
        </Box>

    </AuthenticatedLayout>
</template>
