<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {trans} from "laravel-vue-i18n";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>
<template>
    <div class="flex flex-col sm:flex-row items-center gap-4 rounded-box border border-primary/30 bg-primary/5 p-4 shadow-soft text-xl w-full justify-between"
         v-if="!usePage().props.auth?.user">
        <div class="flex flex-col">
            <div class="text-2xl font-bold">Would you like to give it a try?</div>
            <div class="text-base-content/70 text-base">
                How about creating your own project ideas and experiencing the benefits of our
                AI-assisted tools for defining and refining your project ideas?
            </div>
        </div>
        <div class="p-2 flex justify-center">
            <PrimaryButton @click="router.visit(route('register'))">{{ trans('auth.create_account') }}</PrimaryButton>
        </div>
    </div>
</template>
