<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ListTick from "@/Components/ListTick.vue";
import Arrow from "@/Components/Arrow.vue";

import {router} from "@inertiajs/vue3";
</script>
<template>
  <section class="bg-gray-500 text-center md:p-12 p-6">
    <p class="font-medium text-accent mb-8" id="pricing">Pricing</p>

    <h2 class="max-w-2xl md:mx-auto font-extrabold mb-6 md:mb-8 md:text-5xl text-2xl">
      Turn your ideas into successful project
    </h2>

    <div class="relative flex flex-col lg:flex-row justify-center lg:items-stretch gap-8">

      <div class="relative w-full max-w-lg">
        <div class="relative flex flex-col h-full gap-3 lg:gap-6 z-10 bg-base-100 p-8 rounded-box">
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
          <span class="badge text-xs text-primary-content font-semibold border-0 bg-cyan-500">POPULAR</span>
        </div>

        <div
            class="relative flex flex-col h-full gap-5 lg:gap-8 z-10 bg-base-100 p-8 rounded-box border border-cyan-500">
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
              <ListTick>Unlimited projects</ListTick>
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

    <div class="py-24 px-8 max-w-7xl mx-auto flex flex-col md:flex-row gap-12" id="faq">
      <div class="flex flex-col text-left basis-1/2">
        <p class="inline-block font-semibold text-accent mb-4">FAQ</p>
        <p class="sm:text-4xl text-3xl font-extrabold">Frequently Asked Questions</p>
      </div>
      <ul class="basis-1/2">
        <li>
          <button
              class="relative flex w-full py-5 font-semibold text-left border-t md:text-lg border-base-content/10"
              aria-expanded="true">
            <span class="flex-1  text-accent">How does your tool help me track my ideas?</span>
          </button>
          <div class="opacity-80 overflow-hidden">
            <div class="pb-5 leading-relaxed">
              <div class="space-y-2 leading-relaxed">Our tool allows you to create and organize your
                ideas in a centralized place, so you can easily keep track of them and access them whenever you need.
                You can also add notes, tags, and attachments to your ideas, and share them with your team.
              </div>
            </div>
          </div>
        </li>
        <li>
          <button
              class="relative flex w-full py-5 font-semibold text-left border-t md:text-lg border-base-content/10"
              aria-expanded="true">
            <span class="flex-1  text-accent">How is AI integrated into your tool?</span>
          </button>
          <div class="opacity-80 overflow-hidden">
            <div class="pb-5 leading-relaxed">
              <div class="space-y-2 leading-relaxed">Our tool uses AI to help you define your
                tasks and projects, based on your description. It gives you insights related to potential targets and
                features, benefits for users, etc, that you can use in your project.
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="max-w-3xl bg-base-100 mx-auto rounded-box">
      <div class="flex flex-col items-center p-12 md:p-16">
        <h2 class="text-3xl md:text-5xl mb-8 md:mb-12">Track your ideas</h2>
        <p class="text-lg text-base-content-secondary mb-12 md:mb-16">
          Don't let your ideas escape you: follow them, get help from AI to define them and transform them into
          successful projects.
        </p>
        <PrimaryButton @click="router.visit('register')">
          <Arrow>Try Progr.io</Arrow>
        </PrimaryButton>
      </div>
    </div>

  </section>
</template>
