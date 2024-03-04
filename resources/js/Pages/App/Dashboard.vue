<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, usePage} from '@inertiajs/vue3';
import {price} from '@/Composables/price.js'
import {date} from '@/Composables/date.js'
import {computed} from 'vue'

const subscription = computed(() => usePage().props.auth.subscription)

defineProps({
    invoices: Array,
});
</script>

<template>
    <Head v-bind:title="$t('Dashboard')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Dashboard')"/>
        </template>


        <Box>
            <div v-if="subscription?.is_subscribed">
                <div v-if="subscription?.on_grace_period">
                    {{ $t('dashboard.subscription_end_on', {'date': date(subscription?.end_date)}) }}
                </div>
                <div v-else>
                    {{ $t('dashboard.subscribed') }}
                </div>
            </div>
            <div v-else>
                {{ $t('dashboard.not_subscribed') }}
            </div>
        </Box>

        <Box>
            <h3>Invoices</h3>
            <table class="w-full" v-if="invoices.length > 0">
                <tr class="bg-black">
                    <td class="px-2 border border-gray-400 bg-gray-400 text-gray-800">{{ $t('Creation date') }}</td>
                    <td class="px-2 border border-gray-400 bg-gray-400 text-gray-800">{{ $t('Total') }}</td>
                    <td class="px-2 border border-gray-400 bg-gray-400"></td>
                </tr>
                <tr v-for="invoice in invoices">
                    <td class="px-2 border border-gray-400">{{ date(invoice.created) }}</td>
                    <td class="px-2 border border-gray-400">{{ price(invoice.total / 100, invoice.currency) }}</td>
                    <td class="px-2 border border-gray-400">
                        <a v-bind:href="route('invoices', invoice.id)">
                            {{ $t('Download') }}
                        </a>
                    </td>
                </tr>
            </table>
            <div v-else>
                {{ $t('You don\'t have any invoices.') }}
            </div>
        </Box>


    </AuthenticatedLayout>
</template>

