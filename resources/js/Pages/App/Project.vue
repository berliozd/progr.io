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
import {capitalize, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import getStatuses from "@/Composables/getStatuses.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {trans} from "laravel-vue-i18n";

if (!useStore().projectId) {
    router.visit(route('app.projects'));
}

const project = ref(null)
const getProject = async () => {
    try {
        const response = await axios.get('/api/projects/' + useStore().projectId)
        project.value = response.data
        console.log(project.value);
    } catch (error) {
        console.log(error)
    }
}
getProject();

const statuses = ref(null)
getStatuses().then((response) => {
    statuses.value = response
})

const saveProject = async () => {
    try {
        console.log('SAVING');
        console.log(project.value);
        await axios.patch('/api/projects/' + useStore().projectId, project.value);
        useStore().setToast(trans('app.saved'));
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

const selectProjectStatus = (status) => {
    project.value.status = status.id;
}
const addEmptyNote = (noteType) => {
    if (!noteType) {
        useStore().setToast(trans('app.project.select_note_type'), true)
        return
    }
    project.value.notes.push({
        'project_id': project.value.id,
        'note_type_id': noteType.id,
        'note_type_code': noteType.code,
        'note_type_label': noteType.label,
        'content': '',
    });
    noteTypeToAdd.value = null
    project.value.availableNotesTypes = project.value.availableNotesTypes.filter(type => {
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
        <Box class="space-y-4 relative" v-if="project">
            <div>
                <label for="title">{{ $t('app.project.title') }}:</label>
                <div class="mt-2">
                    <text-input v-model="project.title" name="title" class="w-full"></text-input>
                </div>
            </div>

            <div>
                <label for="description">{{ $t('app.project.description') }}:</label>
                <div class="mt-2">
                    <text-area v-model="project.description" rows="8" class="w-full"></text-area>
                </div>
            </div>

            <details class="collapse bg-gray-300 dark:bg-gray-500 text-gray-800 dark:text-gray-900">
                <summary class="collapse-title text-xl font-medium">{{ $t('app.project.notes') }}</summary>
                <div class="collapse-content">
                    <div v-for="note in project.notes">
                        <div class="flex flex-row items-center pr-2 hover:cursor-pointer">
                            <label>{{ capitalize(note.note_type_label) }}:</label>
                            <AskAiModal :note-type-code="note.note_type_code"
                                        :note-type-label="note.note_type_label"
                                        :project-description="project.description"
                                        :project-title="project.title"
                                        :project-note="note"/>
                        </div>
                        <TextArea v-model="note.content" rows="6" class="w-full"></TextArea>
                    </div>

                    <div class="flex flex-col" v-if="project.availableNotesTypes.length >0">
                        <div>
                            {{ $t('app.project.select_note_type') }}
                        </div>
                        <div class="items-center">
                            <select class="select bg-white mr-2" v-model="noteTypeToAdd">
                                <option v-for="notesType in project.availableNotesTypes" v-bind:value="notesType"
                                        :key='notesType.id'>
                                    {{ capitalize(notesType.label) }}
                                </option>
                            </select>
                            <PrimaryButton @click="addEmptyNote(noteTypeToAdd)">{{ $t('app.project.add_note') }}
                            </PrimaryButton>
                        </div>
                    </div>

                </div>
            </details>

            <div>
                <label for="description" class="block">{{ $t('app.project.status') }}:</label>
                <StatusBadges v-bind:statuses="statuses" v-bind:project-status="project.status"
                              v-bind:on-click="selectProjectStatus"/>
            </div>

            <SaveProjectButton v-bind:on-click="saveProject"></SaveProjectButton>
        </Box>
    </AuthenticatedLayout>
</template>
