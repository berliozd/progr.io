<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";
import StatusBadges from "@/Pages/App/Partials/StatusBadges.vue";
import AskAiModal from "@/Pages/App/Partials/AskAiModal.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {capitalize, reactive, ref, watch} from "vue";
import {useStore} from "@/Composables/store.js";
import getStatuses from "@/Composables/getStatuses.js";
import {trans} from "laravel-vue-i18n";
import debounce from 'lodash.debounce'

const noteTypeToAdd = ref(null)
const dropOverNote = ref(null);
const statuses = ref(null)
const project = reactive({title: '', description: '', status: ''})
const saved = ref(false);
const refreshAfterSave = ref(false);

if (!useStore().projectId) {
  router.visit(route('app.projects'));
}

const getProject = async () => {
  try {
    if (useStore().projectId) {
      const response = await axios.get('/api/projects/' + useStore().projectId)
      Object.assign(project, response.data);
      sortNotes()
      watch(project, () => {
        debouncedSave()
      })
    }
  } catch
      (error) {
    console.log(error)
  }
}

const debouncedSave = debounce(async () => {
  await save()
  if (refreshAfterSave.value) {
    await getProject()
    refreshAfterSave.value = false
  }
}, 1000)

const saveProjectAndRedirect = async () => {
  try {
    await save()
    router.visit('/projects')
  } catch (error) {
    console.log(error)
  }
}

const save = async () => {
  try {
    await axios.patch('/api/projects/' + useStore().projectId, project);
    saved.value = true
    setTimeout(() => {
      saved.value = false
    }, 3000);

  } catch (error) {
    console.log(error)
  }
}

const selectProjectStatus = (status) => {
  project.status = status.id;
}

const addEmptyNote = (noteType) => {
  if (!noteType) {
    useStore().setToast(trans('app.project.select_note_type'), true)
    return
  }
  refreshAfterSave.value = false
  project.notes.push({
    'project_id': project.id,
    'type': {'id': noteType.id, 'label': noteType.label, 'code': noteType.code},
    'content': '',
    'order': maxOrderNotes() + 1
  });
  sortNotes()
  noteTypeToAdd.value = null
  project.availableNotesTypes = project.availableNotesTypes.filter(type => {
    return noteType.id !== type.id
  });
}

const sortNotes = () => {
  project.notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
}

const maxOrderNotes = () => {
  let maxOrder = 0
  project.notes.forEach(note => {
    if (note.order > maxOrder) {
      maxOrder = note.order
    }
  })
  return maxOrder
}

const endDrag = (event, item) => {
  const noteDroppedOverOrder = dropOverNote.value.order;
  dropOverNote.value.order = item.order
  item.order = noteDroppedOverOrder
  sortNotes();
}

const dragOver = (event, item) => {
  event.dataTransfer.dropEffect = 'link'
  dropOverNote.value = item
}

const moveUp = (event, note) => {
  const currentOrder = note.order;
  note.order = previousNote(note).order
  previousNote(note).order = currentOrder
  sortNotes();
}

const moveDown = (event, note) => {
  const currentOrder = note.order;
  note.order = nextNote(note).order
  nextNote(note).order = currentOrder
  sortNotes();
}

const getNoteIndexInArray = (note) => {
  let idx = 0;
  for (let item in project.notes) {
    if (project.notes[item].content === note.content && project.notes[item].type.code === note.type.code) {
      return idx;
    }
    idx++;
  }
  return idx;
}

const previousNote = (note) => {
  const currentNoteIndex = getNoteIndexInArray(note);
  return project.notes[currentNoteIndex - 1]
}

const nextNote = (note) => {
  const currentNoteIndex = getNoteIndexInArray(note);
  return project.notes[currentNoteIndex + 1]
}

const deleteNote = async (event, note) => {
  if (note.id) {
    const index = project.notes.indexOf(note);
    project.notes.splice(index, 1);
    project.availableNotesTypes.push(project.allNotesTypes.find(type => type.id === note.note_type_id))
    await axios.delete('/api/projects_notes/' + note.id);
  }
}

getProject();
getStatuses().then((response) => {
  statuses.value = response
})

</script>
<template>
  <Head v-bind:title="$t('Project')"/>
  <AuthenticatedLayout>
    <template #header>
      <PageHeader v-bind:title="$t('Project')"/>
    </template>

    <Transition
        enter-active-class="transition ease-in-out"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out"
        leave-to-class="opacity-0"
    >
      <p v-if="saved" class="text-sm ">{{ $t('app.project_saved') }}</p>
    </Transition>

    <div class="flex flex-row justify-end" v-if="project && project.id">
      <a v-bind:href="route('app.projects.presentation', {id: project.id})" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-presentation">
          <path d="M2 3h20"/>
          <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/>
          <path d="m7 21 5-5 5 5"/>
        </svg>
      </a>
    </div>

    <Box class="space-y-2 bg-primary/80 relative" v-if="project">
      <label for="title">{{ $t('app.project.title') }} :</label>
      <div class="">
        <TextInput v-model="project.title" name="title" class="w-full"></TextInput>
      </div>
      <label for="description">{{ $t('app.project.description') }} :</label>
      <div class="">
        <TextArea v-model="project.description" rows="8" class="w-full"></TextArea>
      </div>
    </Box>

    <Box class="space-y-4 relative bg-primary/80" v-if="project">
      <div class="mt-2">
        <details
            class="collapse collapse-arrow bg-neutral/70 border text-white/70">
          <summary class="collapse-title text-xl mb-2 font-medium">{{ $t('app.project.notes') }}</summary>
          <div class="collapse-content">
            <div v-for="note in project.notes" class="my-4 hover:cursor-grab note" :key="note.id" draggable="true"
                 @dragend="endDrag($event, note)" @dragover="dragOver($event, note)"
                 @dragover.prevent>
              <div class="flex flex-row justify-between mb-2">
                <div class="flex flex-row w-fit">
                  <label class="text-xs sm:text-base">{{ capitalize(note.type.label) }}:</label>
                  <AskAiModal :note-type-code="note.type.code"
                              :note-type-label="note.type.label"
                              :project-description="project.description"
                              :project-title="project.title"
                              :project-note="note" @change="refreshAfterSave = true"/>
                </div>
                <div class="flex flex-row hover:cursor-pointer space-x-2">
                  <div class="flex flex-row w-12 justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-up-from-line" @click="moveUp($event, note)"
                         v-if="previousNote(note)">
                      <path d="m18 9-6-6-6 6"/>
                      <path d="M12 3v14"/>
                      <path d="M5 21h14"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-down-from-line" @click="moveDown($event, note)"
                         v-if="nextNote(note)">
                      <path d="M19 3H5"/>
                      <path d="M12 21V7"/>
                      <path d="m6 15 6 6 6-6"/>
                    </svg>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-trash-2 hover:cursor-pointer" @click="deleteNote($event, note)">
                    <path d="M3 6h18"/>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    <line x1="10" x2="10" y1="11" y2="17"/>
                    <line x1="14" x2="14" y1="11" y2="17"/>
                  </svg>
                </div>
              </div>
              <TextArea v-model="note.content" rows="6" class="w-full" @input="refreshAfterSave = true"></TextArea>
            </div>

            <div class="flex flex-col" v-if="project.availableNotesTypes?.length > 0">
              <div>
                {{ $t('app.project.select_note_type') }}
              </div>
              <div class="items-center">
                <select class="select bg-white mr-2" @change="addEmptyNote(noteTypeToAdd)"
                        v-model="noteTypeToAdd">
                  <option v-for="notesType in project.availableNotesTypes"
                          v-bind:value="notesType"
                          :key='notesType.id'>
                    {{ capitalize(notesType.label) }}
                  </option>
                </select>
              </div>
            </div>

          </div>
        </details>
      </div>
    </Box>

    <Box class="space-y-4 relative bg-primary/80" v-if="project">
      <details class="collapse collapse-arrow bg-neutral/70 border text-white/70 mt-2">
        <summary class="collapse-title text-xl mb-2 font-medium">{{ $t('app.project.competitors') }}</summary>
        <div class="collapse-content">
          <div v-for="competitor in project.competitors" class="my-4 hover:cursor-grab competitor" :key="competitor.id"
               draggable="true" @dragend="endDrag($event, competitor)"
               @dragover="dragOver($event, competitor)" @dragover.prevent>
            <div class="flex flex-row justify-between mb-2">
              <div class="flex flex-col w-full">
                <label class="text-xs sm:text-base">{{ capitalize(competitor.name) }}:</label>
                <a :href="competitor.url" target="_blank">{{ competitor.url }}</a>
                <details class="collapse collapse-arrow bg-neutral/70 border text-white/70 mt-2">
                  <summary class="collapse-title text-xl mb-2 font-medium">{{ $t('app.project.competitor.notes') }}</summary>
                  <div class="collapse-content">
                    <div v-for="competitorNote in competitor.notes" class="my-4 hover:cursor-grab competitor" :key="competitorNote.id"
                         draggable="true" @dragend="endDrag($event, competitorNote)"
                         @dragover="dragOver($event, competitorNote)" @dragover.prevent>
                      <div class="flex flex-row justify-between mb-2">
                        <div class="flex flex-col w-full">
                          <label class="text-xs sm:text-base">{{ capitalize(competitorNote.type.label) }}:</label>
                          <TextArea v-model="competitorNote.content" rows="6" class="w-full" @input="refreshAfterSave = true"></TextArea>
                        </div>
                      </div>
                    </div>
                  </div>
                </details>
              </div>
            </div>
          </div>
        </div>
      </details>
    </Box>

    <Box class="bg-primary/80">
      <label for="description" class="block mb-2">{{ $t('app.project.status') }}:</label>
      <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status"
                    v-bind:on-click="selectProjectStatus"/>
    </Box>

    <Transition
        enter-active-class="transition ease-in-out"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out"
        leave-to-class="opacity-0"
    >
      <p v-if="saved" class="text-sm ">{{ $t('app.project_saved') }}</p>
    </Transition>

    <SaveProjectButton v-bind:on-click="saveProjectAndRedirect"></SaveProjectButton>

  </AuthenticatedLayout>
</template>
