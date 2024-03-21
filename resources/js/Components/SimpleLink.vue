<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    class: String
})
const cssClass = (props.class ?? '') + ' font-semibold ' +
    ' hover:text-neutral text-neutral-content ';
</script>
<template>
    <Link v-bind:href="href" v-bind:class="cssClass">
        <slot/>
    </Link>
</template>
