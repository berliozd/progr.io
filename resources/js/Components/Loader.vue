<script setup>
import {useStore} from "@/Composables/store.js";
</script>
<template>
<span v-if="useStore().loading"
      class="flex flex-col loading loading-spinner
      bg-neutral-content
      mx-auto absolute w-24 opacity-20 m-auto left-0 right-0 z-50"
      style="z-index: 10000">
</span>
</template>
