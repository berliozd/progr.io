<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import axios from "axios";
import {getActiveLanguage, trans} from "laravel-vue-i18n";
import TextInput from "@/Components/TextInput.vue";
import {useStore} from "@/Composables/store.js";

const ideas = ref([]);
const aiAvailable = ref(true)
const loading = ref(false)
const context = ref('')

const askAI = async () => {
  if (!context.value) {
    useStore().setToast(trans('app.ideas.no_context'), true)
    return
  }
  if (aiAvailable.value) {
    useStore().setIsLoading(true)
    loading.value = true
    const response = await axios.post('/api/ai/', {'context': getContext(), 'question': getQuestion()})
    loading.value = false
    let results = JSON.parse(response.data.response)
    ideas.value = []
    for (const result in results) {
      ideas.value.push(results[result])
    }
    useStore().setIsLoading(false)
  }
}

const getLanguage = () => {
  if (getActiveLanguage() === 'en')
    return 'english';
  if (getActiveLanguage() === 'fr')
    return 'french';
}

const getQuestion = () => {
  return 'Can you give me 5 ideas. ' +
      'Your reply must be returned in json format. It should be an array. Each item in the array must have a title and description. Do not add any extra characters in your reply like JSON.' +
      'You will reply in ' + getLanguage();
}

const getContext = () => {
  return 'As a indie hacker I would like you to give project ideas. The ideas should be related to ' + context.value;
}

const add = async (title, description) => {
  try {
    let project = {'title': title, 'description': description, 'status': 1}
    await axios.post('/api/projects/', project);
    useStore().setToast(trans('app.ideas.project_added'));
  } catch (error) {
    console.log(error)
  }
}

</script>
<template>
  <Head v-bind:title="$t('Projects')"/>
  <AuthenticatedLayout>
    <template #header>
      <PageHeader v-bind:title="$t('app.ideas.ideas')"/>
    </template>
    <Box class="relative">
      <label for="context" class=" inline-block">{{ $t('app.ideas.give_context') }}</label>
      <p class="mb-2 text-sm text-neutral-content/50">{{ $t('app.ideas.give_context_details') }}</p>
      <div class="flex flex-row justify-between space-x-4">
        <TextInput name="context" v-model="context" rows="8" class="sm:w-4/5" max-length="100"></TextInput>
        <PrimaryButton @click="askAI" v-bind:disabled="loading" class="sm:w-1/5 justify-center">{{ $t('app.project.ask_ai') }}</PrimaryButton>
      </div>
      <div v-for="idea in ideas" class="rounded border p-2 my-4 flex flex-col border-spacing-y-1">
        <div>
          {{ $t('app.project.title') }} : {{ idea.title }}
        </div>
        <div>
          {{ $t('app.project.description') }} : {{ idea.description }}
        </div>
        <div>
          <PrimaryButton @click="add(idea.title, idea.description)">
            {{ $t('app.ideas.add_as_project') }}
          </PrimaryButton>
        </div>
      </div>
    </Box>
  </AuthenticatedLayout>
</template>
