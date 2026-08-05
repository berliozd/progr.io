<script setup>
import {Link, usePage} from "@inertiajs/vue3";

const props = defineProps({simple: {type: Boolean, value: false}})
const page = usePage();
const appName = page.props.app.name;
const cssClass = () => {
    return !props.simple ? 'border border-base-300 rounded-box h-auto' : '';
}
</script>
<template>
    <Link :href="route(usePage().props.app.home_route)">
        <div class="mt-1 flex flex-col items-center gap-0.5 p-1.5 font-bold tracking-tight text-base-content"
             :class="cssClass()">
            <img src="/img/icon.png" width="30px" height="30px" v-bind:alt="appName" v-bind:title="appName">
            {{ appName }}
        </div>
    </Link>
</template>
