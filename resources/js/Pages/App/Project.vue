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


if (!useStore().projectId) {
  router.visit(route('app.projects'));
}

const project = reactive({
  title: '',
  description: '',
  status: ''
})

const getProject = async () => {
  try {
    if (useStore().projectId) {
      const response = await axios.get('/api/projects/' + useStore().projectId)
      Object.assign(project, response.data);
      watch(project, () => {
        debouncedSave()
      })
    }
  } catch (error) {
    console.log(error)
  }
}
getProject();

const debouncedSave = debounce(() => {
  save()
  console.log('Send API request')
}, 2000)


const statuses = ref(null)
getStatuses().then((response) => {
  statuses.value = response
})

const saveProjectAndRedirect = async () => {
  console.log('saveProjectAndRedirect');
  try {
    await save()
    router.visit('/projects')
  } catch (error) {
    console.log(error)
  }
}

const saved = ref(false);
const save = async () => {
  console.log('save');
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
  project.notes.push({
    'project_id': project.id,
    'note_type_id': noteType.id,
    'note_type_code': noteType.code,
    'note_type_label': noteType.label,
    'content': '',
  });
  noteTypeToAdd.value = null
  project.availableNotesTypes = project.availableNotesTypes.filter(type => {
    return noteType.id !== type.id
  });
}

const noteTypeToAdd = ref(null)
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

    <Box class="space-y-2 bg-primary/80 relative" v-if="project">
      <label for="title">{{ $t('app.project.title') }} :</label>
      <div class="">
        <text-input v-model="project.title" name="title" class="w-full"></text-input>
      </div>
      <label for="description">{{ $t('app.project.description') }} :</label>
      <div class="">
        <text-area v-model="project.description" rows="8" class="w-full"></text-area>
      </div>
    </Box>

    <Box class="space-y-4 relative bg-primary/80" v-if="project">
      <label for="notes">{{ $t('app.project.notes') }} :</label>
      <div class="mt-2">
        <details
            class="collapse collapse-arrow bg-neutral text-white/70">
          <summary class="collapse-title text-xl mb-2 font-medium">{{ $t('app.project.notes') }}</summary>
          <div class="collapse-content">
            <div v-for="note in project.notes" class="mt-4" :key="note.id">
              <div class="flex flex-row justify-between sm:justify-normal items-center mb-2 hover:cursor-pointer">
                <label class="text-xs sm:text-base">{{ capitalize(note.note_type_label) }}:</label>
                <AskAiModal :note-type-code="note.note_type_code"
                            :note-type-label="note.note_type_label"
                            :project-description="project.description"
                            :project-title="project.title"
                            :project-note="note"/>
              </div>
              <TextArea v-model="note.content" rows="6" class="w-full"></TextArea>
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
