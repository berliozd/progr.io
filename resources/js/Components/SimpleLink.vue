<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    class: String
})
const cssClass = (props.class ?? '') + ' font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white';
</script>
<template>
    <Link v-bind:href="href" v-bind:class="cssClass">
        <slot/>
    </Link>
</template>
