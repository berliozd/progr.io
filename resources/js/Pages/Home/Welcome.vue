<script setup>
import {Head} from '@inertiajs/vue3';
import {trans} from "laravel-vue-i18n";
import Nav from "@/Pages/Home/Partials/Nav.vue";
import SectionOne from "@/Pages/Home/Partials/SectionOne.vue";
import SectionTwo from "@/Pages/Home/Partials/SectionTwo.vue";
import SectionThree from "@/Pages/Home/Partials/SectionThree.vue";
import SectionFour from "@/Pages/Home/Partials/SectionFour.vue";
import Footer from "@/Pages/Home/Partials/Footer.vue";

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  }
});
</script>

<template>
  <Head v-bind:title="trans('Welcome')"/>
  <Nav v-bind:can-login="canLogin" v-bind:can-register="canRegister"/>
  <SectionOne/>
  <SectionTwo/>
  <SectionThree/>
  <SectionFour/>
  <Footer/>
</template>
