<script setup>
import Box from "@/Components/Box.vue";
import PresentationLayout from "@/Layouts/PresentationLayout.vue";

import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {capitalize, reactive, ref} from "vue";

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
}

getProject();
</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <PresentationLayout>
    <Box class="space-y-2 bg-primary/80 relative" v-if="projectFound">

      <div class="flex flex-col m-4 rounded-xl border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <div>{{ project.title }}</div>
      </div>

      <div class="flex flex-col m-4 rounded-xl border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ project.description }}</div>
      </div>

      <template v-for="note in project.notes" class="" :key="note.id">
        <div class="flex flex-col m-4 rounded-xl border shadow-2xl bg-neutral/70">
          <h2 class="bg-white/20 rounded-t-xl p-2 text-2xl">{{ capitalize(note.note_type_label) }}:</h2>
          <div class="flex flex-row">
            <div class=" p-2">
              <svg v-if="note.note_type_code === 'benefits'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-users">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
              <svg v-if="note.note_type_code === 'monetization'" xmlns="http://www.w3.org/2000/svg" width="24"
                   height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-gem">
                <path d="M6 3h12l4 6-10 13L2 9Z"/>
                <path d="M11 3 8 9l4 13 4-13-3-6"/>
                <path d="M2 9h20"/>
              </svg>
              <svg v-if="note.note_type_code === 'features'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-feather">
                <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/>
                <line x1="16" x2="2" y1="8" y2="22"/>
                <line x1="17.5" x2="9" y1="15" y2="15"/>
              </svg>
              <svg v-if="note.note_type_code === 'targets'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-target">
                <circle cx="12" cy="12" r="10"/>
                <circle cx="12" cy="12" r="6"/>
                <circle cx="12" cy="12" r="2"/>
              </svg>
              <svg v-if="note.note_type_code === 'pricing'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-badge-dollar-sign">
                <path
                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                <path d="M12 18V6"/>
              </svg>
              <svg v-if="note.note_type_code === 'domains'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-hard-drive">
                <line x1="22" x2="2" y1="12" y2="12"/>
                <path
                    d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                <line x1="6" x2="6.01" y1="16" y2="16"/>
                <line x1="10" x2="10.01" y1="16" y2="16"/>
              </svg>
              <svg v-if="note.note_type_code === 'competitors'" xmlns="http://www.w3.org/2000/svg" width="24"
                   height="24"
                   viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-swords">
                <polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"/>
                <line x1="13" x2="19" y1="19" y2="13"/>
                <line x1="16" x2="20" y1="16" y2="20"/>
                <line x1="19" x2="21" y1="21" y2="19"/>
                <polyline points="14.5 6.5 18 3 21 3 21 6 17.5 9.5"/>
                <line x1="5" x2="9" y1="14" y2="18"/>
                <line x1="7" x2="4" y1="17" y2="20"/>
                <line x1="3" x2="5" y1="19" y2="21"/>
              </svg>
            </div>
            <pre class="text-wrap font-sans p-2">{{ note.content }}</pre>
          </div>
        </div>
      </template>

    </Box>
    <Box class="space-y-2 bg-primary/80 relative" v-else>
      {{ $t('app.no_projects_found') }}
    </Box>


  </PresentationLayout>
</template>
