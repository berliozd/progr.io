<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";
import StatusBadges from "@/Pages/App/Partials/StatusBadges.vue";

import {Head, router, usePage} from '@inertiajs/vue3';
import axios from "axios";
import {reactive, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";
import getStatuses from "@/Composables/getStatuses.js";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";

const statuses = ref(null)
getStatuses().then((response) => {
  statuses.value = response
})

const project = reactive({
  title: {type: String, value: ''},
  description: {type: String, value: ''},
  status: {type: Number, value: 1},
})

const save = async () => {
  if (!validate()) {
    return;
  }
  if (project.title.value === '') {
    usePage().props.error = trans('app.project.title_error')
  }
  try {
    await axios.post('/api/projects/', project);
    useStore().setToast('Created!');
    router.visit('/projects')
  } catch (error) {
    console.log(error)
  }
}

const validate = () => {
  usePage().props.error = ''
  if (project.title.value === '') {
    usePage().props.error = trans('app.project.title_error')
    return false;
  }
  if (project.description.value === '') {
    usePage().props.error = trans('app.project.description_error')
    return false;
  }
  return true;
}

const selectProjectStatus = (status) => {
  project.status.value = status.id;
}

</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <AuthenticatedLayout>
    <template #header>
      <PageHeader v-bind:title="$t('New Project')"/>
    </template>
    <Box class="space-y-4 relative bg-primary/70">
      <ErrorAlert v-bind:error="usePage().props.error" v-if="usePage().props.error"/>
      <label for="title">{{ $t('app.project.title') }}:</label>
      <div class="mt-2">
        <text-input v-model="project.title.value" name="title" class="w-full"></text-input>
      </div>
      <label for="description">{{ $t('app.project.description') }}:</label>
      <div class="mt-2">
        <text-area v-model="project.description.value" rows="8" class="w-full"></text-area>
      </div>
      <label for="description" class="block">{{ $t('app.project.status') }}:</label>
      <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status.value"
                    v-bind:on-click="selectProjectStatus"></StatusBadges>
    </Box>
    <SaveProjectButton v-bind:on-click="save"></SaveProjectButton>
  </AuthenticatedLayout>
</template>
