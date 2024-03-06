<script setup>
import SimpleLink from "@/Components/SimpleLink.vue";

import setMode from "@/Composables/setMode.js";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

setMode();
const cssClass = 'ms-4';

</script>

<template>
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-center bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <SimpleLink v-if="$page.props.auth.user" v-bind:href="route('dashboard')">{{ $t('Get in') }}</SimpleLink>
            <template v-else>
                <SimpleLink v-bind:href="route('login')">{{ $t('Log in') }}</SimpleLink>
                <SimpleLink v-if="canRegister" v-bind:href="route('register')" v-bind:class="cssClass">
                    {{ $t('Register') }}
                </SimpleLink>
            </template>
        </div>
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <slot/>
        </div>
    </div>
</template>
