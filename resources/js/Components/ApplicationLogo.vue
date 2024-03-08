<script setup>
import {Link, usePage} from "@inertiajs/vue3";

const props = defineProps({simple: {type: Boolean, value: false}})
const page = usePage();
const appName = page.props.app.name;
const cssClass = () => {
    return !props.simple ? 'border h-auto rounded' : '';
}
</script>
<template>
    <Link :href="route(usePage().props.app.home_route)">
        <div class="mt-1 flex flex-col  p-1" :class="cssClass()">
            <img src="/img/icon.png" width="30px" height="30px" v-bind:alt="appName" v-bind:title="appName">
            {{ appName }}
        </div>
    </Link>
</template>
