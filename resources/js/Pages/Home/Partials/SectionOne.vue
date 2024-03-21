<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ListTick from "@/Components/ListTick.vue";
import Arrow from "@/Components/Arrow.vue";

import {router} from "@inertiajs/vue3";
</script>

<template>
  <section class="md:p-12 p-6 mx-auto">
    <div class="px-8 pb-6 flex flex-col gap-10 lg:gap-14 items-center text-center">
      <h1 class="md:text-7xl text-3xl my-6 font-extrabold leading-normal">
        Turn your
        <span class="border-b-8 border-dashed border-primary/50 italic">ideas</span>
        <br>
        into
        <span class="border-b-8 border-dashed border-primary/50 italic">successful projects</span>
      </h1>
      <div class="text-lg max-w-xl mx-auto">
        Progr.io is the tool that helps you keep track of your ideas and turn them into successful projects.
        <br>
        You can easily follow the progress of your ideas, collaborate with your team, get help from AI, and stay
        organized.
        <br>
        <br>
        Don't let your ideas slip away - track them and turn them into successful projects.
      </div>
      <ul class="space-y-1.5">
        <li class="flex items-center justify-start gap-2">
          <ListTick>Start for free</ListTick>
        </li>
        <li class="flex items-center justify-start gap-2">
          <ListTick>Track your projects</ListTick>
        </li>
        <li class="flex items-center justify-start gap-2">
          <ListTick>Get help from AI</ListTick>
        </li>
      </ul>
      <PrimaryButton @click="router.visit('register')">
        <Arrow>Get started</Arrow>
      </PrimaryButton>
    </div>
  </section>
</template>
