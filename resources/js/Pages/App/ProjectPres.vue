<script setup>
import Box from "@/Components/Box.vue";
import PresentationLayout from "@/Layouts/PresentationLayout.vue";

import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {capitalize, reactive, ref} from "vue";

const project = reactive({
  title: '',
  description: '',
  status: ''
})
const projectFound = ref(false);

const getProject = async () => {
  try {
    const id = window.location.href.split('/').pop();
    const response = await axios.get('/api/projects/' + id)
    projectFound.value = true
    Object.assign(project, response.data);
  } catch (error) {
    console.log(error)
  }
}
getProject();


</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <PresentationLayout>
    <Box class="space-y-2 bg-primary/80 relative" v-if="projectFound">

      <h2>{{ $t('app.project.title') }}:</h2>
      <div class="flex flex-col m-4 rounded border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <div>{{ project.title }}</div>
      </div>

      <h2>{{ $t('app.project.description') }}:</h2>
      <div class="flex flex-col m-4 rounded border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ project.description }}</div>
      </div>

      <template v-for="note in project.notes" class="" :key="note.id">
        <h2>{{ capitalize(note.note_type_label) }}:</h2>
        <div class="flex flex-col m-4 rounded border p-4 shadow-2xl bg-neutral/70">
          <pre class="text-wrap font-sans">{{ note.content }}</pre>
        </div>
      </template>

    </Box>
    <Box class="space-y-2 bg-primary/80 relative" v-else>
      {{ $t('app.no_projects_found') }}
    </Box>


  </PresentationLayout>
</template>
