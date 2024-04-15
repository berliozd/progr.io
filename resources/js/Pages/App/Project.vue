<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";
import StatusBadges from "@/Pages/App/Partials/StatusBadges.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Collapsable from "@/Components/Collapsable.vue";
import Competitor from "@/Pages/App/Partials/Competitor.vue";

import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {inject, nextTick, reactive, ref, watch} from "vue";
import {trans} from "laravel-vue-i18n";
import debounce from 'lodash.debounce'
import {useStore} from "@/Composables/store.js";
import getStatuses from "@/Composables/getStatuses.js";
import aiAvailable from "@/Composables/App/aiAvailable.js";
import reallyAskAi from "@/Composables/App/reallyAskAi.js";
import Notes from "@/Pages/App/Partials/Notes.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import UpAndDown from "@/Components/UpAndDown.vue";
import SavedLabel from "@/Components/SavedLabel.vue";

const smoothScroll = inject('smoothScroll')
const statuses = ref(null)
const project = reactive({title: '', description: '', status: ''})
const refreshAfterSave = ref(false);
const competitors = ref([]);
const ai = ref(true);
const loading = ref(false)
const newCompetitor = reactive({name: '', description: '', url: ''});

const resetNewCompetitor = () => {
  newCompetitor.name = '';
  newCompetitor.description = '';
  newCompetitor.url = '';
};
const projectId = window.location.href.split('/').pop();
if (!projectId) {
  router.visit(route('app.projects'));
}

const getProject = async () => {
  console.log('getProject');
  try {
    const response = await axios.get('/api/projects/' + projectId)
    Object.assign(project, response.data);
    sortNotes(project)
    watch(project, () => {
      debouncedSave()
    })
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
    await axios.patch('/api/projects/' + projectId, project);
    useStore().setSaved(trans('app.project_saved'));
  } catch (error) {
    console.log(error)
  }
}

const selectProjectStatus = (status) => {
  project.status = status.id;
}

const sortNotes = (project) => {
  project.notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
  project.competitors.sort((competitorA, competitorB) => competitorA.order > competitorB.order ? 1 : -1);
  for (let competitorIndex in project.competitors) {
    project.competitors[competitorIndex].notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
  }
}

const searchCompetitor = () => {
  aiAvailable().then((available) => {
    if (available) {
      loading.value = true
      useStore().setIsLoading(true)
      reallyAskAi(
          project.title + ' - ' + project.description,
          'Give me a list of project that are potential competitors for my project idea. ' +
          'Your answer must be separated by break line, without politeness phrase, no bulleted list, no numbering. ' +
          'I want the name, a brief description, and the website url for each separated by |. ' +
          'Example of output: ' + 'Name|Description|Url' +
          'Do not add number or bullet in front of each item.' +
          'I want only competitors with accessible website.',
      ).then((response) => {
        useStore().setIsLoading(false)
        loading.value = false
        const results = response.split(/\n/g);
        competitors.value = []
        for (let i = 0; i < results.length; i++) {
          const result = results[i];
          if (result !== '') {
            let [name, description, url] = result.split('|');
            if (name && description && url) {
              competitors.value.push({name, description, url});
            }
          }
        }
      })
    } else {
      console.log('cannot search competitor');
    }
  })
}

const maxOrderCompetitors = (competitors) => {
  let maxOrder = 0
  competitors.forEach(competitor => {
    if (competitor.order > maxOrder) {
      maxOrder = competitor.order
    }
  })
  return maxOrder
}

const addCompetitor = async (competitorData, competitors) => {
  try {
    let competitor = {
      'name': competitorData.name,
      'description': competitorData.description,
      'url': competitorData.url,
      'project_id': project.id,
      'order': maxOrderCompetitors(project.competitors) + 1,
      'notes': [],
    }
    refreshAfterSave.value = true
    project.competitors.push(competitor)
    console.log(project.competitors);
    useStore().setToast(trans('app.project.competitor_added'));
    const index = competitors.indexOf(competitorData);
    competitors.splice(index, 1);
    scrollToBottomCompetitors()
  } catch (error) {
    console.log(error)
  }
}

const deleteCompetitor = (competitor, competitors) => {
  const index = competitors.indexOf(competitor);
  competitors.splice(index, 1);
}

const bottomCompetitors = ref(null)

const scrollToBottomCompetitors = () => {
  nextTick(() => {
    smoothScroll({
      scrollTo: bottomCompetitors.value,
      hash: '#bottomCompetitors'
    })
  })
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

    <div class="flex flex-row justify-end" v-if="project && project.id">
      <SavedLabel/>
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
      <Collapsable :title="$t('app.project.notes')">
        <Notes :all-notes-types="project.allNotesTypes" :available-notes-types="project.availableNotesTypes"
               :notes="project.notes" :context="project.title + '-' + project.description" :notes-parent-id="project.id"
               :notes-parent-id-field-name="'project_id'"
               :api-url="'\/api/projects_notes\/'"
               @change="refreshAfterSave = true" @start-add-note="refreshAfterSave = false"/>
      </Collapsable>
    </Box>


    <Box class="space-y-4 relative bg-primary/80" v-if="project">

      <Collapsable :title="$t('app.project.competitors')">

        <Collapsable :title="$t('app.project.competitor.search')" :open="true">

          <div class="flex flex-col sm:flex-row text-xs justify-between">
            <div class="flex flex-row space-x-2 mb-1">
              <span class="sm:text-lg">{{ $t('app.project.competitor.get_help_from_ai') }}</span>
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
            <PrimaryButton @click="searchCompetitor" v-bind:disabled="loading">
              {{ $t('app.project.competitor.search') }}
            </PrimaryButton>
          </div>

          <template v-if="!ai">
            <div class=" alert alert-warning m-4 w-fit">
              <div>{{ $t('app.ai_not_available') }}</div>
              <PrimaryButton @click="window.location.href = route('subscribe.checkout')">
                {{ $t('app.subscribe') }}
              </PrimaryButton>
            </div>
          </template>

          <div v-for="competitor in competitors"
               class="rounded-lg border shadow-lg bg-neutral-content/40 shadow-secondary-content/50 p-2 my-4 flex flex-row justify-between">
            <div class="flex flex-col">
              <div class="mb-2 w-fit">
                <span class="underline font-bold">{{ $t('app.project.competitor.name') }}</span> :
                {{ competitor.name }}
              </div>
              <div class="mb-2 break-word">
                <span class="underline font-bold">{{ $t('app.project.competitor.description') }}</span> :
                {{ competitor.description }}
              </div>
              <div class="mb-2 break-all text-xs sm:text-base">
                <span class="underline font-bold">{{ $t('app.project.competitor.url') }}</span> :
                <a :href="competitor.url" target="_blank">{{ competitor.url }}</a>
              </div>
            </div>
            <div class="flex flex-col justify-end">
              <PrimaryButton @click="addCompetitor(competitor, competitors)">
                {{ $t('app.add') }}
              </PrimaryButton>
            </div>
          </div>


          <div class="border rounded p-2 my-2 flex flex-col justify-end">
            <Competitor :competitor="newCompetitor"/>
            <div class="flex justify-end">
              <PrimaryButton
                  @click="addCompetitor(newCompetitor, competitors); resetNewCompetitor()">
                {{ $t('app.add') }}
              </PrimaryButton>
            </div>
          </div>

        </Collapsable>

        <Collapsable :title="$t('app.project.competitors')">
          <span v-if="project?.competitors?.length === 0">{{ $t('app.project.no_competitors') }}</span>
          <div v-for="competitor in project.competitors"
               class="my-4 border p-4 rounded-lg bg-neutral-content/40 shadow-lg shadow-secondary-content/40"
               :key="competitor.id">
            <div class="flex flex-col mb-2">
              <div class="flex justify-end">
                <UpAndDown :item="competitor" :items="project.competitors"/>
                <DeleteModal :question="'Are you sure you want to delete this competitor ?'"
                             :api-url="'\/api/competitors\/'" :id="competitor.id"
                             :confirmation-button-text="'Delete competitor'"
                             @deleted="deleteCompetitor(competitor, project.competitors)"/>
              </div>
              <Competitor :competitor="competitor"/>
              <Collapsable :title="$t('app.project.competitor.notes')">
                <Notes :all-notes-types="competitor.allNotesTypes"
                       :available-notes-types="competitor.availableNotesTypes"
                       :notes="competitor.notes" :context="competitor.name + '-' + competitor.description"
                       :notes-parent-id="competitor.id"
                       :notes-parent-id-field-name="'competitor_id'"
                       :api-url="'\/api/competitors_notes\/'"
                       @change="refreshAfterSave = true" @start-add-note="refreshAfterSave = false"/>
              </Collapsable>
            </div>
          </div>
          <div ref="bottomCompetitors"></div>
        </Collapsable>

      </Collapsable>
    </Box>

    <Box class="bg-primary/80">
      <label for="description" class="block mb-2">{{ $t('app.project.status') }}:</label>
      <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status"
                    v-bind:on-click="selectProjectStatus"/>
    </Box>

    <div class="flex flex-row justify-end">
      <SavedLabel/>
      <div>
        <SaveProjectButton v-bind:on-click="saveProjectAndRedirect"></SaveProjectButton>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
