<script setup>
import Box from "@/Components/Box.vue";
import PresentationLayout from "@/Layouts/PresentationLayout.vue";

import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {capitalize, reactive, ref} from "vue";
import NoteLogo from "@/Pages/App/Partials/NoteLogo.vue";

const project = reactive({title: '', description: '', status: ''})
const projectFound = ref(false);

const getProject = async () => {
  try {
    const id = window.location.href.split('/').pop();
    const response = await axios.get('/api/projects/' + id)
    projectFound.value = true
    Object.assign(project, response.data);
    sortNotes()
  } catch (error) {
    console.log(error)
  }
}

const sortNotes = () => {
  project.notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
  project.competitors.sort((competitorA, competitorB) => competitorA.order > competitorB.order ? 1 : -1);
  for (let competitorIndex in project.competitors) {
    project.competitors[competitorIndex].notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
  }
}

getProject();
</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <PresentationLayout>
    <Box class="space-y-2 bg-primary/80 relative" v-if="projectFound">

      <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <div>{{ project.title }}</div>
      </div>

      <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ project.description }}</div>
      </div>

      <template v-for="note in project.notes" class="" :key="note.id">
        <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70">
          <h2 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ capitalize(note.type.label) }}:</h2>
          <div class="flex flex-row">
            <NoteLogo :note="note"/>
            <pre class="text-wrap font-sans p-2">{{ note.content }}</pre>
          </div>
        </div>
      </template>

      <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70"
           v-if="project.competitors && project.competitors.length">
        <h2 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ $t('app.project.competitors') }}:</h2>

        <template v-for="competitor in project.competitors" class="" :key="competitor.id">
          <div class="flex flex-col m-4 rounded-lg border shadow-2xl bg-neutral/70">
            <h2 class="bg-white/30 rounded-t-lg p-2 text-xl">{{ capitalize(competitor.name) }}:</h2>
            <div class="">
              <div class="m-4">{{ competitor.description }}</div>
              <div class="m-4"><a :href="competitor.url" target="_blank" class="underline">{{ competitor.url }}</a></div>
              <template v-for="competitorNote in competitor.notes" class="" :key="competitorNote.id">
                <div class="flex flex-col rounded-b-lg border-t shadow-2xl bg-neutral/70">
                  <h2 class="bg-white/10 p-2 text-xl">{{ capitalize(competitorNote.type.label) }}:</h2>
                  <div class="flex flex-row ">
                    <NoteLogo :note="competitorNote"/>
                    <pre class="text-wrap font-sans p-2 rounded-b-lg">{{ competitorNote.content }}</pre>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </template>
      </div>

    </Box>
    <Box class="space-y-2 bg-primary/80 relative" v-else>
      {{ $t('app.no_projects_found') }}
    </Box>

  </PresentationLayout>
</template>
