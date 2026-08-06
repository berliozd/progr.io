<script setup>

import Prices from "@/Components/Prices.vue";
</script>
<template>
    <section class="text-center md:p-12 p-6 bg-base-200">
        <p class="section-eyebrow mb-4" id="pricing">Pricing</p>

        <h2 class="max-w-2xl md:mx-auto font-extrabold mb-4 md:text-5xl text-2xl tracking-tight">
            Turn your ideas into successful project
        </h2>

        <p class="max-w-xl mx-auto text-base-content/70 mb-6 md:mb-8">
            Pay once, own your credits. No subscription, no recurring charges, no surprise renewals.
        </p>

        <Prices/>

    </section>
</template>
