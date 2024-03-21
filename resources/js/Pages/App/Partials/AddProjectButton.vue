<script setup>
import {router} from "@inertiajs/vue3";
</script>
<template>
    <div class="flex w-full rounded border p-2 hover:cursor-pointer "
         @click="router.visit(route('app.projects.new'))">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-plus mx-auto ">
            <path d="M5 12h14"/>
            <path d="M12 5v14"/>
        </svg>
    </div>
</template>
