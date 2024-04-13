<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import AddProjectButton from "@/Pages/App/Partials/AddProjectButton.vue";
import SimpleLink from "@/Components/SimpleLink.vue";
import Badge from "@/Components/Badge.vue";
import DeleteModal from "@/Components/DeleteModal.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {computed, ref} from "vue";
import {truncate} from 'lodash';
import {useStore} from "@/Composables/store.js";

const getData = async () => {
  try {
    const response = await axios.get('/api/projects/')
    projects.value = response.data
    useStore().setIsLoading(false)
  } catch (error) {
    console.log(error)
  }
}
const projects = ref(null)
const hasProject = computed(() => {
  if (projects.value) {
    return projects.value.length > 0
  }
  return false;
});
getData()
const navToProject = (project) => {
  useStore().setProjectId(project.id);
  router.visit(route('app.projects.detail'));
}
useStore().setIsLoading(true)
</script>
<template>
  <Head v-bind:title="$t('Projects')"/>
  <AuthenticatedLayout>
    <template #header>
      <PageHeader v-bind:title="$t('Projects')"/>
    </template>
    <Box v-if="!useStore().loading" class="relative">
      <div v-if="!hasProject" class="my-4">{{ $t('app.no_projects') }}</div>
      <div v-else class="my-4">{{ $t('app.nb_projects', {'nb': projects.length}) }}</div>
      <div class="flex flex-grow justify-between">
        <SimpleLink v-bind:href="route('app.ideas')">
          <div class="rounded border p-1 hover:border-neutral">
            {{ $t('app.out_of_ideas') }}
            <div class="flex flex-row ml-10 space-x-2 text-xs items-center">
              <span class="underline">{{ $t('app.project.ask_ai_help') }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-bot-message-square">
                <path d="M12 6V2H8"/>
                <path d="m8 18-4 4V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2Z"/>
                <path d="M2 12h2"/>
                <path d="M9 11v2"/>
                <path d="M15 11v2"/>
                <path d="M20 12h2"/>
              </svg>
            </div>
          </div>
        </SimpleLink>
        <AddProjectButton/>
      </div>
      <div class="overflow-auto h-80 my-2">
        <div class="grid grid-cols-6 w-full mb-2">
          <div class="text-lg col-span-3">{{ $t('app.project.title') }}</div>
          <div class="flex justify-around text-lg col-span-2">{{ $t('app.project.status') }}</div>
          <div class=""></div>
        </div>
        <div class="grid grid-cols-6 w-full p-2
                    hover:cursor-pointer [&:nth-child(even)]:bg-neutral
                    [&:nth-child(even)]:hover:bg-accent/20 hover:bg-accent/20" v-for="project in projects">
          <div @click="navToProject(project);" class=" col-span-3">
            {{ truncate(project.title, {'length': 75}) }}
          </div>
          <div class="flex justify-around  col-span-2" @click="navToProject(project);">
            <Badge :label="project.status_label"></Badge>
          </div>
          <div class="flex justify-end ">
            <DeleteModal :id="project.id" :api-url="'\/api/projects\/'" @deleted="getData"
                         :question=" $t('app.project.deletion_confirmation_question')"
                         :confirmation-button-text="$t('app.project.delete')"/>
          </div>
        </div>
      </div>
    </Box>
  </AuthenticatedLayout>
</template>
