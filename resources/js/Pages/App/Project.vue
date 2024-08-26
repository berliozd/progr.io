<script setup>
import Box from "@/Components/Box.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import SaveProjectButton from "@/Pages/App/Partials/SaveProjectButton.vue";
import Collapsable from "@/Components/Collapsable.vue";
import Competitor from "@/Pages/App/Partials/Competitor.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import UpAndDown from "@/Components/UpAndDown.vue";
import SavedLabel from "@/Components/SavedLabel.vue";
import Notes from "@/Pages/App/Partials/Notes.vue";
import Visibilities from "@/Pages/App/Partials/Visibilities.vue";
import Statuses from "@/Pages/App/Partials/Statuses.vue";
import ProjectActions from "@/Pages/App/Partials/ProjectActions.vue";
import AutoPopulations from "@/Pages/App/Partials/AutoPopulations.vue";

import {reactive, ref, watch} from "vue";
import {Head, router} from '@inertiajs/vue3';
import axios from "axios";
import {trans} from "laravel-vue-i18n";
import debounce from 'lodash.debounce'
import {useStore} from "@/Composables/store.js";
import statuses from "@/Composables/statuses.js";
import {sortProjectChildren} from "@/Composables/App/useProject.js";
import {idByCode} from "@/Composables/autoPopulations.js";
import Ad from "@/Pages/Catalog/Partials/AdMultiplex.vue";

const project = reactive({title: '', description: '', status: ''})
const refreshAfterSave = ref(false);
const autoPopulationProcessing = ref(null);

const projectId = window.location.href.split('/').pop();
if (!projectId) {
    router.visit(route('app.projects'));
}

const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + projectId)
        Object.assign(project, response.data);
        sortProjectChildren(project)
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

const deleteCompetitor = (competitor, competitors) => {
    const index = competitors.indexOf(competitor);
    competitors.splice(index, 1);
}

idByCode('processing').then(
    (response) => {
        autoPopulationProcessing.value = response
    }
);

getProject();
</script>
<template>
    <Head v-bind:title="$t('Project')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Project')"/>
        </template>

        <ProjectActions :project="project"/>

        <Box class="space-y-2 bg-primary/80 relative" v-if="project">
            <label for="title">{{ $t('app.project.title') }} :</label>
            <div class="">
                <TextInput v-model="project.title" name="title" class="w-full"
                           :disabled="project.auto_population === autoPopulationProcessing"></TextInput>
            </div>
            <label for="description">{{ $t('app.project.description') }} :</label>
            <div class="">
                <TextArea v-model="project.description" rows="8" class="w-full"
                          :disabled="project.auto_population === autoPopulationProcessing"></TextArea>
            </div>
        </Box>

        <template v-if="project.auto_population === autoPopulationProcessing">
            <div class="alert alert-info">
                {{ $t('app.project.auto_populations.in_progress') }}
            </div>
            <div class="alert alert-info">
                {{ $t('app.project.auto_populations.in_progress_description') }}
            </div>
        </template>

        <template v-if="project && project.auto_population !== autoPopulationProcessing">
            <Box class="space-y-4 relative bg-primary/80">
                <Collapsable :title="$t('app.project.notes')">
                    <Notes :all-notes-types="project.allNotesTypes" :available-notes-types="project.availableNotesTypes"
                           :notes="project.notes" :title="project.title" :description="project.description"
                           :notes-parent-id="project.id" :notes-parent-id-field-name="'project_id'"
                           :api-url="'\/api/projects_notes\/'"
                           @change="refreshAfterSave = true" @start-add-note="refreshAfterSave = false"/>
                </Collapsable>
            </Box>
            <Box class="space-y-4 relative bg-primary/80">
                <Collapsable :title="$t('app.project.competitors')">
                    <span v-if="project?.competitors?.length === 0">{{ $t('app.project.no_competitors') }}</span>
                    <div v-for="competitor in project.competitors"
                         class="my-4 border p-4 rounded-lg bg-neutral-content/40 shadow-lg shadow-secondary-content/40"
                         :key="competitor.id">
                        <div class="flex flex-col mb-2">
                            <div class="flex justify-end">
                                <UpAndDown :item="competitor" :items="project.competitors" :use-pivot="true"/>
                                <DeleteModal :question="'Are you sure you want to delete this competitor ?'"
                                             :api-url="'\/api/competitor_project\/'" :id="competitor.pivot.id"
                                             :confirmation-button-text="'Delete competitor'"
                                             @deleted="deleteCompetitor(competitor, project.competitors)"/>
                            </div>
                            <Competitor :competitor="competitor"/>
                            <Collapsable :title="$t('app.project.competitor.notes')">
                                <Notes :all-notes-types="competitor.allNotesTypes"
                                       :available-notes-types="competitor.availableNotesTypes"
                                       :notes="competitor.notes"
                                       :title="competitor.name"
                                       :description="competitor.description"
                                       :notes-parent-id="competitor.id"
                                       :notes-parent-id-field-name="'competitor_id'"
                                       :api-url="'\/api/competitors_notes\/'"
                                       :is-competitor="true"
                                       :read-only="true"
                                       @change="refreshAfterSave = true"
                                       @start-add-note="refreshAfterSave = false"/>
                            </Collapsable>
                        </div>
                    </div>
                </Collapsable>
            </Box>

            <Box class="bg-primary/80">
                <Statuses :project="project" :all-statuses="project.allStatuses"/>
                <hr>
                <Visibilities :project="project" :all-visibilities="project.allVisibilities"/>
                <hr>
                <AutoPopulations :project="project" :all-auto-populations="project.allAutoPopulations"/>
            </Box>

            <div class="flex flex-row justify-end">
                <SavedLabel/>
                <div>
                    <SaveProjectButton v-bind:on-click="saveProjectAndRedirect"></SaveProjectButton>
                </div>
            </div>

        </template>
        <Ad :el="'bottom'"/>

    </AuthenticatedLayout>
</template>
