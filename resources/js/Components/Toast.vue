<script setup>
import {useStore} from "@/Composables/store.js";

const baseCss = 'toast toast-bottom toast-start transition duration-700 ease-out z-50'
const css = () => {
    return useStore().toastVisible ? baseCss + ' opacity-100' : baseCss + ' opacity-0'
};
</script>
<template>
    <div v-bind:class="css()">
        <div class="alert alert-info">
            <span>{{ useStore().toastText }}</span>
        </div>
    </div>
</template>
