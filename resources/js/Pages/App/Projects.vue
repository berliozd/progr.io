<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import DeleteProject from "@/Pages/App/Partials/DeleteProject.vue";
import AddProjectButton from "@/Pages/App/Partials/AddProjectButton.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {computed, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {truncate} from 'lodash';
import Badge from "@/Components/Badge.vue";

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
      <AddProjectButton/>
      <div class="overflow-auto h-80 my-2">
        <div class="grid grid-cols-3 w-full mb-2">
          <div class="text-lg">{{ $t('app.project.title') }}</div>
          <div class="flex justify-around text-lg">{{ $t('app.project.status') }}</div>
          <div class=""></div>
        </div>
        <div class="grid grid-cols-3 w-full p-2 mb-2
                    hover:cursor-pointer [&:nth-child(even)]:bg-neutral
                    [&:nth-child(even)]:hover:bg-accent/20 hover:bg-accent/20" v-for="project in projects">
          <div @click="navToProject(project);" class="">
            {{ truncate(project.title, {'length': 50}) }}
          </div>
          <div class="flex justify-around" @click="navToProject(project);">
            <Badge :label="project.status_label"></Badge>
          </div>
          <div class="flex justify-end">
            <DeleteProject v-bind:project-id="project.id"/>
          </div>
        </div>
      </div>
      <AddProjectButton/>
    </Box>
  </AuthenticatedLayout>
</template>
