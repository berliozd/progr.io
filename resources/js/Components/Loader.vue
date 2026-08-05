<script setup>
import {useStore} from "@/Composables/store.js";
</script>
<template>
<span v-if="useStore().loading"
      class="loading loading-spinner text-primary mx-auto w-16 m-auto left-0 right-0 fixed top-1/2 -translate-y-1/2"
      style="z-index: 10000">
</span>
</template>
