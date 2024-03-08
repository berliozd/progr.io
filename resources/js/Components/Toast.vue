<script setup>
import {useStore} from "@/Composables/store.js";

const css = () => {
    const baseCss = 'toast toast-bottom toast-start transition duration-700 ease-out z-50'
    return useStore().toastVisible ? baseCss + ' opacity-100' : baseCss + ' opacity-0'
};
const alertCss = () => {
    console.log(useStore().toastError);
    return useStore().toastError ? 'alert alert-error' : 'alert alert-info '
}
</script>
<template>
    <div v-bind:class="css()">
        <div :class="alertCss()">
            <span>{{ useStore().toastText }}</span>
        </div>
    </div>
</template>
