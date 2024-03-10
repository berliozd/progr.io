<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";

import {Head, usePage} from '@inertiajs/vue3';
import price from '@/Composables/price.js'
import date from '@/Composables/date.js'
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

      <table class="table" v-if="invoices.length > 0">
        <thead>
        <tr class="text-lg">
          <th class="text-gray-800 dark:text-gray-400">{{ $t('Creation date') }}</th>
          <th class="text-gray-800 dark:text-gray-400 text-center">{{ $t('Total') }}</th>
          <th class="text-center text-gray-800 dark:text-gray-400"></th>
        </tr>
        </thead>
        <tbody>
        <tr class="" v-for="invoice in invoices">
          <td class="">{{ date(invoice.created) }}</td>
          <td class="text-center">{{ price(invoice.total / 100, invoice.currency) }}</td>
          <td class="text-center hover:cursor-pointer">
            <a v-bind:href="route('invoices', invoice.id)">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                   class="w-5 h-5 mx-auto">
                <path
                    d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z"/>
                <path
                    d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z"/>
              </svg>
            </a>
          </td>
        </tr>
        </tbody>
      </table>
      <div v-else>
        {{ $t('You don\'t have any invoices.') }}
      </div>
    </Box>

  </AuthenticatedLayout>
</template>

