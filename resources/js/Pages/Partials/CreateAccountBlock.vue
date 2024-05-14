<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {trans} from "laravel-vue-i18n";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>
<template>
    <div class="flex flex-col sm:flex-row rounded-lg border p-4 shadow-2xl bg-primary/30 text-xl w-full justify-between"
         v-if="!usePage().props.auth?.user">
        <div class="flex flex-col">
            <div class="text-2xl">Would you like to give it a try?</div>
            <div class="">
                How about creating your own project ideas and experiencing the benefits of our
                AI-assisted tools for defining and refining your project ideas?
            </div>
        </div>
        <div class="p-2 flex justify-center">
            <PrimaryButton @click="router.visit(route('register'))">{{ trans('auth.create_account') }}</PrimaryButton>
        </div>
    </div>
</template>
