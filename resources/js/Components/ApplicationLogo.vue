<script setup>
import {usePage} from "@inertiajs/vue3";

const page = usePage();
const appName = page.props.app.name;
</script>
<template>
    <img src="/img/icon.svg" width="100" v-bind:alt="appName" v-bind:title="appName">
</template>
