<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {useStore} from "@/Composables/store.js";

import {Head, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import axios from "axios";
import {getActiveLanguage, trans} from "laravel-vue-i18n";

const ideas = ref([]);
const aiAvailable = ref(true)
const loading = ref(false)
const context = ref('')
const usedAiCredits = ref(false)

const askAI = async () => {
  await checkUserRights;
  if (!context.value) {
    useStore().setToast(trans('app.ideas.no_context'), true)
    return
  }
  if (aiAvailable.value) {
    try {
      useStore().setIsLoading(true)
      loading.value = true
      const response = await axios.post('/api/ai/', {'context': getContext(), 'question': getQuestion()})
      await incrementUserUsedCredits()
      let results = response.data.response.split(/\n/g);
      ideas.value = []
      for (let i = 0; i < results.length; i++) {
        let result = results[i];
        if (result !== '') {
          let [title, description] = result.split('|');
          if (title && description) {
            ideas.value.push({title, description});
          }
        }
      }
    } finally {
      useStore().setIsLoading(false)
      loading.value = false
    }
  }
}

const incrementUserUsedCredits = async () => {
  await axios.patch(
      '/api/user/' + usePage().props.auth.user.id, {
        'field': 'used_ai_credits',
        'value': ++usedAiCredits.value
      }
  )
}

const getLanguage = () => {
  if (getActiveLanguage() === 'en')
    return 'english';
  if (getActiveLanguage() === 'fr')
    return 'french';
}

const getQuestion = () => {
  return 'Can you give me 5 ideas.' +
      'Each idea must be separated by a break line.' +
      'Each idea must have a title and a description.' +
      'Title and description must be separated by |.' +
      'Do not add bullets or numbers before each ideas.' +
      'Do not and introduction text before listing teh ideas.' +
      'Content should be in ' + getLanguage();
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

const checkUserRights = async () => {
  const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
  usedAiCredits.value = userResponse.data.used_ai_credits
  aiAvailable.value = usePage().props.auth.subscription.is_subscribed
      || userResponse.data.used_ai_credits < usePage().props.app.free_ai_credits
}

</script>
<template>
  <Head v-bind:title="$t('app.ideas.ideas_generator')"/>
  <AuthenticatedLayout>
    <template #header>
      <PageHeader v-bind:title="$t('app.ideas.ideas_generator')"/>
    </template>

    <Box>
      <details class="collapse collapse-arrow" open>
        <summary class="collapse-title text-xl font-medium">
          {{ $t('app.ideas.generator_introduction') }}
        </summary>
        <div class="collapse-content space-y-4">
          <div>{{ $t('app.ideas.generator_line1') }}</div>
          <div>{{ $t('app.ideas.generator_line2') }}</div>
          <div>{{ $t('app.ideas.generator_line3') }}</div>
        </div>
      </details>
    </Box>

    <Box class="relative">
      <label for="context" class=" inline-block">{{ $t('app.ideas.give_context') }}</label>
      <p class="mb-2 text-sm text-neutral-content/50">{{ $t('app.ideas.give_context_details') }}</p>
      <div class="flex flex-row justify-between space-x-4">
        <TextInput name="context" v-model="context" rows="8" class="w-2/3 sm:w-5/6" max-length="100"></TextInput>
        <PrimaryButton @click="askAI" v-bind:disabled="loading" class="px-0 sm:px-4 w-1/3 sm:w-1/6 justify-center">
          {{ $t('app.project.ask_ai') }}
        </PrimaryButton>
      </div>
      <div v-for="idea in ideas" class="rounded border p-2 my-4 flex flex-col border-spacing-y-1">
        <div class="mb-2">
          <span class="underline font-bold">{{ $t('app.project.title') }}</span> : {{ idea.title }}
        </div>
        <div class="mb-2">
          <span class="underline font-bold">{{ $t('app.project.description') }}</span> : {{ idea.description }}
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
