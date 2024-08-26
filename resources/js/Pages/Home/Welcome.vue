<script setup>
import Nav from "@/Pages/Home/Partials/Nav.vue";
import SectionOne from "@/Pages/Home/Partials/SectionOne.vue";
import SectionTwo from "@/Pages/Home/Partials/SectionTwo.vue";
import SectionThree from "@/Pages/Home/Partials/SectionThree.vue";
import SectionFour from "@/Pages/Home/Partials/SectionFour.vue";
import Footer from "@/Pages/Home/Partials/Footer.vue";
import SectionFive from "@/Pages/Home/Partials/SectionFive.vue";
import SectionSix from "@/Pages/Home/Partials/SectionSix.vue";
import Ad from "@/Pages/Catalog/Partials/AdMultiplex.vue";

import {Head} from '@inertiajs/vue3';
import {ref} from "vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});

const keywords = ref(null)
const description = ref(null)

async function getMeta() {
    await axios.get('/api/seo/meta').then(response => {
        keywords.value = response.data.keywords
        description.value = response.data.description
    })
}
getMeta()
</script>

<template>
    <Head>
        <title>{{$t('Welcome')}}</title>
        <meta name="description" :content="description">
        <meta name="keywords" :content="keywords">
        <link rel="canonical" :href="route('home')">
    </Head>
    <Nav v-bind:can-login="canLogin" v-bind:can-register="canRegister"/>
    <SectionOne/>
    <SectionTwo/>
    <SectionThree/>
    <SectionFour/>
    <SectionFive/>
    <SectionSix/>
    <Ad/>
    <Footer/>
</template>
