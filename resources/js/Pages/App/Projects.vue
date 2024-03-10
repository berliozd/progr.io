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
import date, {getShortDatePattern} from "@/Composables/date.js";
import {truncate} from 'lodash';
import {getActiveLanguage} from "laravel-vue-i18n";

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
const dateFormat = ref(null)
const setDateFormat = () => {
  if (getActiveLanguage() === 'fr') {
    console.log('get fr locale');
    dateFormat.value = 'dd/MM/yyyy'
  }
  if (getActiveLanguage() === 'en') {
    console.log('get en locale');
    dateFormat.value = 'MM/dd/yyyy'
  }
}
setDateFormat();
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
        <table class="table">
          <thead>
          <tr>
            <th class="text-gray-800 dark:text-gray-400 text-lg">{{ $t('app.project.title') }}</th>
            <th class="text-center text-gray-800 dark:text-gray-400 text-lg">{{ $t('app.project.status') }}</th>
            <th class="text-center text-gray-800 dark:text-gray-400 text-lg">
              <div class="flex flex-col">
                <div>
                  {{ $t('app.project.updated_date') }}
                </div>
                <div class="text-xs">
                  ({{ getShortDatePattern() }})
                </div>
              </div>
            </th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr class="hover:cursor-pointer" v-for="project in projects">
            <td @click="navToProject(project);">{{ truncate(project.title, {'length': 100}) }}</td>
            <td class="text-center" @click="navToProject(project);">{{ project.status_label }}</td>
            <td class="text-center" @click="navToProject(project);">
              {{ date(project.updated_at) }}
            </td>
            <td>
              <DeleteProject v-bind:project-id="project.id"/>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <AddProjectButton/>
    </Box>
  </AuthenticatedLayout>
</template>
