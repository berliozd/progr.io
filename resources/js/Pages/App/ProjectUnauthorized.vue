<script setup>
import PresentationLayout from "@/Layouts/PresentationLayout.vue";
import Box from "@/Components/Box.vue";
import {Head} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({'code': null})
const goTo = (url) => {
  window.location.href = url
}
</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <PresentationLayout>
    <Box class="space-y-2 relative flex flex-row justify-center">
      <div class="flex flex-col space-y-4">
        <div class="flex flex-row justify-center">
          <ApplicationLogo/>
        </div>
        <template v-if="code === 'only_members'">
          <p>{{ $t('app.project.only_members_project') }}</p>
          <div class="flex flex-row justify-center">
            <PrimaryButton @click="goTo(route('register'))">{{ $t('auth.register') }}</PrimaryButton>
          </div>
        </template>
        <template v-if="code === 'private'">
          <p>{{ $t('app.project.private_project') }}</p>
        </template>
      </div>
    </Box>
  </PresentationLayout>
</template>
