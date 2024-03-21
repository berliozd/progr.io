<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Arrow from "@/Components/Arrow.vue";

import {router} from "@inertiajs/vue3";
</script>
<template>
  <section class=" text-center md:p-12 p-6">

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

  </section>
</template>
