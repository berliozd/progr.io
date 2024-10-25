<template>
    <div class="flex flex-col lg:flex-row justify-center lg:items-stretch gap-8">

        <div class=" w-full max-w-lg border rounded-xl">
            <div class=" flex flex-col h-full gap-3 lg:gap-6 z-10 bg-base-300 p-8 rounded-box">
                <p class="text-lg lg:text-xl font-bold">Starter</p>
                <div class="flex gap-2">
                    <p class="text-5xl font-extrabold">$9</p>
                    <div class="flex flex-col justify-end">
                        <p class="text-xs  uppercase font-semibold">USD</p>
                    </div>
                </div>
                <div class="bg-accent/20 rounded border text-center">30 credits</div>
                <ul class="space-y-2.5 leading-relaxed text-base flex-1">
                    <li class="flex items-center gap-2">
                        <ListTick>$0.30 per credit</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>1 fully defined project</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>1 "ideas generator" search</ListTick>
                    </li>
                </ul>
                <div class="space-y-2">
                    <PrimaryButton v-if="buy" @click="goTo(route('checkout_offer', {id:0}))">
                        <Arrow>Buy credits</Arrow>
                    </PrimaryButton>
                    <PrimaryButton v-else @click="router.visit('register')">
                        <Arrow>Get Progr.io</Arrow>
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <div class="relative w-full max-w-lg">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                <span class="badge text-xs text-primary-content font-semibold border-0 bg-success">POPULAR</span>
            </div>

            <div
                class="relative flex flex-col h-full gap-5 lg:gap-8 z-10 bg-base-300 p-8 rounded-box border border-success">
                <p class="text-lg lg:text-xl font-bold">Basic</p>
                <div class="flex gap-2">
                    <div class="flex flex-col justify-end mb-[4px] text-lg ">
                        <p class="relative">
                            <span class="absolute bg-base-content h-[1.5px] inset-x-0 top-[53%]"></span>
                            <span class="text-base-content/80">$27</span>
                        </p>
                    </div>
                    <p class="text-5xl font-extrabold">$19</p>
                    <div class="flex flex-col justify-end mb-[4px]">
                        <p class="text-xs uppercase font-semibold">
                            USD <span class="text-base-content/50 lowercase"></span>
                        </p>
                    </div>
                </div>
                <div class="bg-accent/20 rounded border text-center">90 credits</div>
                <ul class="space-y-2.5 leading-relaxed text-base flex-1">
                    <li class="flex items-center gap-2">
                        <ListTick>$0.21 per credit</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>30% discount</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>3 fully defined projects</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>3 "ideas generator" searches</ListTick>
                    </li>
                </ul>

                <div class="space-y-2">
                    <PrimaryButton v-if="buy" @click="goTo(route('checkout_offer', {id:1}))">
                        <Arrow>Buy credits</Arrow>
                    </PrimaryButton>
                    <PrimaryButton v-else @click="router.visit('register')">
                        <Arrow>Get Progr.io</Arrow>
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <div class=" w-full max-w-lg border rounded-xl">
            <div class=" flex flex-col h-full gap-3 lg:gap-6 z-10 bg-base-300 p-8 rounded-box">
                <p class="text-lg lg:text-xl font-bold">Advanced</p>
                <div class="flex gap-2">
                    <div class="flex flex-col justify-end mb-[4px] text-lg ">
                        <p class="relative">
                            <span class="absolute bg-base-content h-[1.5px] inset-x-0 top-[53%]"></span>
                            <span class="text-base-content/80">$90</span>
                        </p>
                    </div>
                    <p class="text-5xl font-extrabold">$59</p>
                    <div class="flex flex-col justify-end">
                        <p class="text-xs  uppercase font-semibold">USD</p>
                    </div>
                </div>
                <div class="bg-accent/20 rounded border text-center">300 credits</div>
                <ul class="space-y-2.5 leading-relaxed text-base flex-1">
                    <li class="flex items-center gap-2">
                        <ListTick>$0.19 per credit</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>35% discount</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>10 fully defined projects</ListTick>
                    </li>
                    <li class="flex items-center gap-2">
                        <ListTick>10 "ideas generator" searches</ListTick>
                    </li>
                </ul>
                <div class="space-y-2">
                    <PrimaryButton v-if="buy" @click="goTo(route('checkout_offer', {id:2}))">
                        <Arrow>Buy credits</Arrow>
                    </PrimaryButton>
                    <PrimaryButton v-else @click="router.visit('register')">
                        <Arrow>Get Progr.io</Arrow>
                    </PrimaryButton>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup>
import ListTick from "@/Components/ListTick.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Arrow from "@/Components/Arrow.vue";
import {router} from "@inertiajs/vue3";

const goTo = (url) => {
    window.location.href = url
}

const props = defineProps({
    buy: false
})
</script>