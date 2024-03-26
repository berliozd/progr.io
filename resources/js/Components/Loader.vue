<script setup>
import {useStore} from "@/Composables/store.js";
</script>
<template>
<span v-if="useStore().loading"
      class="loading loading-spinner bg-neutral-content mx-auto w-24 opacity-50 m-auto left-0 right-0 fixed"
      style="z-index: 10000">
</span>
</template>
