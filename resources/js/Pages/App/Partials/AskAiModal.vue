<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

import axios from "axios";
import {capitalize, ref} from "vue";
import {getActiveLanguage} from "laravel-vue-i18n";
import {usePage} from "@inertiajs/vue3";
import {Clipboard} from 'v-clipboard'
import {useStore} from "@/Composables/store.js";

const props = defineProps({
  projectTitle: null,
  projectDescription: null,
  projectNote: null,
  noteTypeCode: null,
  noteTypeLabel: null,
})

const isShowModal = ref(false)
const aiResponse = ref('');
const loading = ref(false)
const aiAvailable = ref(true)
const usedAiCredits = ref(false)

const checkUserRights = async () => {
  const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
  usedAiCredits.value = userResponse.data.used_ai_credits
  aiAvailable.value = usePage().props.auth.subscription.is_subscribed
      || userResponse.data.used_ai_credits < usePage().props.app.free_ai_credits
}

function getContext() {
  let context = props.projectTitle + ' - ' + props.projectDescription;
  if (typeof props.title === "object") {
    context = props.projectTitle.value + ' - ' + props.projectDescription.value;
  }
  return context;
}

const askAI = async () => {
  await checkUserRights()
  if (aiAvailable.value) {
    loading.value = true
    const response = await axios.post('/api/ai/', {'context': getContext(), 'question': getQuestion()})
    loading.value = false
    aiResponse.value = response.data.response
    await incrementUserUsedCredits()
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

const getQuestion = () => {
  let question = getBaseQuestion();
  question += ', in your answer use bullets points';
  question += ', use carriage return';
  question += ', reply in ' + getLanguage();
  return question;
}

const getLanguage = () => {
  if (getActiveLanguage() === 'en')
    return 'english';
  if (getActiveLanguage() === 'fr')
    return 'french';
}

const getBaseQuestion = () => {
  // return 'give disagreements my users get when they do not use my tool, your answers should be a list of answers formatted in a human speaking manner starting by "oh sheet I"'
  if (props.noteTypeCode === 'benefits') {
    return 'give me benefits for future users'
  }
  if (props.noteTypeCode === 'monetization') {
    return 'give me how I can monetize it'
  }
  if (props.noteTypeCode === 'pricing') {
    return 'give me possible pricing plans and features associated'
  }
  if (props.noteTypeCode === 'features') {
    return 'give me possible features'
  }
  if (props.noteTypeCode === 'domains') {
    return 'give me possible short and cool domains names'
  }
  if (props.noteTypeCode === 'targets') {
    return 'tell me who could be interested by my tool'
  }
}

const showModal = () => {
  aiResponse.value = ''
  isShowModal.value = true
  askAI()
}

const hideModal = () => {
  isShowModal.value = false
}

const useForNote = () => {
  props.projectNote.content = aiResponse
  useStore().setToast('Field filled with AI response.')
  hideModal()
}

const copy = () => {
  Clipboard.copy(aiResponse.value)
  useStore().setToast('Copied to clipboard.')
}

const goTo = (url) => {
  window.location.href = url
}
</script>

<template>

  <div class="flex flex-row ml-10 space-x-2 text-xs items-center" @click="showModal">
    <span class="underline">{{ $t('app.project.ask_ai_help') }}</span>
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

  <Modal :show="isShowModal">

    <div class="p-4 w-full space-y-4 flex flex-col" v-if="aiAvailable">
      <div class="flex flex-row w-full justify-between">
        <div>
          {{ $t('app.project.note', {'label': capitalize(noteTypeLabel)}) }}
        </div>
        <div class="space-x-2 items-center">
          <span class="loading loading-spinner loading-s" v-show="loading"></span>
          <PrimaryButton @click="askAI" v-bind:disabled="loading">
            {{ $t('app.project.ask_ai') }}
          </PrimaryButton>
        </div>
      </div>
      <div>
        {{ $t('app.project.question_asked') }}
        <div class="italic">"{{ getBaseQuestion() }}"</div>
      </div>
      <TextArea v-model="aiResponse" rows="8" class="w-full"></TextArea>
      <div class="flex flex-row justify-between">
        <SecondaryButton @click="hideModal">{{ $t('app.cancel') }}</SecondaryButton>
        <div class="flex flex-row justify-between space-x-5">
          <SecondaryButton v-if="aiResponse" @click="copy" :title="$t('app.project.copy')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-clipboard-copy">
              <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
              <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
              <path d="M16 4h2a2 2 0 0 1 2 2v4"/>
              <path d="M21 14H11"/>
              <path d="m15 10-4 4 4 4"/>
            </svg>
          </SecondaryButton>
          <SecondaryButton v-if="aiResponse" @click="useForNote" :title="$t('app.project.use_for_note') ">
            {{ $t('app.project.use_for_note') }}
          </SecondaryButton>
        </div>
      </div>
    </div>

    <div class="flex flex-col space-y-2 p-4" v-else>
      <div class="flex flex-row justify-between alert alert-error">
        {{ $t('app.ai_not_available') }}
        <PrimaryButton @click="goTo(route('subscribe.checkout'))">{{ $t('app.subscribe') }}</PrimaryButton>
      </div>
      <SecondaryButton @click="hideModal" class="w-fit">{{ $t('app.cancel') }}</SecondaryButton>
    </div>

  </Modal>
</template>
