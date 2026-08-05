<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    class: String
})
const cssClass = (props.class ?? '') + ' font-semibold ' +
    ' text-base-content hover:text-primary transition-colors duration-150 ';
</script>
<template>
    <Link v-bind:href="href" v-bind:class="cssClass">
        <slot/>
    </Link>
</template>
