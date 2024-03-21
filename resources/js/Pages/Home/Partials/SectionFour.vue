<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ListTick from "@/Components/ListTick.vue";
import Arrow from "@/Components/Arrow.vue";

import {router} from "@inertiajs/vue3";
</script>
<template>
  <section class="text-center md:p-12 p-6 bg-neutral">
    <p class="font-medium text-accent mb-8" id="pricing">Pricing</p>

    <h2 class="max-w-2xl md:mx-auto font-extrabold mb-6 md:mb-8 md:text-5xl text-2xl">
      Turn your ideas into successful project
    </h2>

    <div class="relative flex flex-col lg:flex-row justify-center lg:items-stretch gap-8">

      <div class="relative w-full max-w-lg">
        <div class="relative flex flex-col h-full gap-3 lg:gap-6 z-10 bg-base-300 p-8 rounded-box">
          <p class="text-lg lg:text-xl font-bold">Free</p>
          <p class="mt-2">Get a taste of progr.io - try it now</p>
          <div class="flex gap-2">
            <p class="text-5xl font-extrabold">$0</p>
            <div class="flex flex-col justify-end">
              <p class="text-xs  uppercase font-semibold">USD</p>
            </div>
          </div>
          <ul class="space-y-2.5 leading-relaxed text-base flex-1">
            <li class="flex items-center gap-2">
              <ListTick>3 projects</ListTick>
            </li>
            <li class="flex items-center gap-2">
              <ListTick>16 AI credits</ListTick>
            </li>
          </ul>
          <div class="space-y-2">
            <PrimaryButton @click="router.visit('register')">
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
          <p class="text-lg lg:text-xl font-bold">Full access</p>
          <p class="text-base-content/80 mt-2">All features unlimited</p>
          <div class="flex gap-2">
            <div class="flex flex-col justify-end mb-[4px] text-lg ">
              <p class="relative">
                <span class="absolute bg-base-content h-[1.5px] inset-x-0 top-[53%]"></span>
                <span class="text-base-content/80">$20</span>
              </p>
            </div>
            <p class="text-5xl font-extrabold">$10</p>
            <div class="flex flex-col justify-end mb-[4px]">
              <p class="text-xs uppercase font-semibold">USD <span class="text-base-content/50 lowercase">/ month</span></p>
            </div>
          </div>
          <ul class="space-y-2.5 leading-relaxed text-base flex-1">
            <li class="flex items-center gap-2">
              <ListTick><span class="bg-accent/20">Unlimited</span> projects</ListTick>
            </li>
            <li class="flex items-center gap-2">
              <ListTick><span><span class="bg-accent/20 px-1">Unlimited</span> AI credits </span></ListTick>
            </li>
          </ul>

          <div class="space-y-2">
            <PrimaryButton @click="router.visit('register')">
              <Arrow>Get Progr.io</Arrow>
            </PrimaryButton>
          </div>
        </div>
      </div>

    </div>

  </section>
</template>
