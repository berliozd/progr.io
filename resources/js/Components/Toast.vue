<script setup>
import {useStore} from "@/Composables/store.js";

const css = () => {
    const baseCss = 'toast toast-bottom toast-start transition duration-700 ease-out'
    return useStore().toastVisible ? baseCss + ' opacity-100' : baseCss + ' opacity-0'
};
const alertCss = () => {
    return useStore().toastError ? 'alert alert-error' : 'alert alert-info '
}
</script>
<template>
    <div v-bind:class="css()" style="z-index: 1000000">
        <div :class="alertCss()">
            <span>{{ useStore().toastText }}</span>
        </div>
    </div>
</template>
