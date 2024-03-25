<script setup>
import {router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>
<template>
  <div class="">
    <PrimaryButton @click="router.visit(route('app.projects.new'))" class="flex justify-around">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
           class="lucide lucide-plus mx-auto ">
        <path d="M5 12h14"/>
        <path d="M12 5v14"/>
      </svg>
    </PrimaryButton>
  </div>
</template>
