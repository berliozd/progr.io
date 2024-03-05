<script setup>
import {usePage} from "@inertiajs/vue3";

const page = usePage();
const appName = page.props.app.name;
</script>
<template>
    <div class="mt-1 flex flex-col border h-auto rounded p-1">
        <img src="/img/icon.png" width="30px" height="30px" v-bind:alt="appName" v-bind:title="appName">
        {{ appName }}
    </div>
</template>
