<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

import {ref} from "vue";
import aiAvailable from "@/Composables/App/aiAvailable.js";
import reallyAskAi from "@/Composables/App/reallyAskAi.js";
import sendMail from "@/Composables/App/sendMail.js";
import {getActiveLanguage, trans} from "laravel-vue-i18n";
import {useStore} from "@/Composables/store.js";
import isEmail from "is-email";

const props = defineProps({
  title: null,
  description: null,
  projectId: null
})
const isShowModal = ref(false)
const aiResponse = ref('');
const recipient = ref('');
const title = ref(trans('app.project.send.title_prefix') + props.title);
const context = ref(props.title + ' - ' + props.description);
const loading = ref(false)
const sending = ref(false)
const ai = ref(true);
const errors = ref([])

const askAI = async () => {
  useStore().setIsLoading(true);
  aiAvailable().then((aiAvailable) => {
    useStore().setIsLoading(false);
    if (aiAvailable) {
      ai.value = true
      loading.value = true
      useStore().setIsLoading(true);
      reallyAskAi(context.value, getQuestion()).then((response) => {
        useStore().setIsLoading(false);
        aiResponse.value = response
        loading.value = false
      })
    } else {
      ai.value = false
    }
  })
}

const getQuestion = () => {
  let question = 'Write a email telling that I am sharing this idea and would like feedback from the recipient.';
  question += ', write in ' + getLanguage()
  question += ',do not add the subject';
  question += ', integrate this link in your answer using a A tag :' + ' ' + route(
      'app.projects.presentation',
      props.projectId
  );
  return question;
}
const getLanguage = () => {
  if (getActiveLanguage() === 'en')
    return 'english';
  if (getActiveLanguage() === 'fr')
    return 'french';
}


const showModal = () => {
  aiResponse.value = ''
  isShowModal.value = true
  askAI()
}

const hideModal = () => {
  isShowModal.value = false
}

const gotTo = (url) => {
  window.location.href = url
}

const send = () => {
  checkForm()
  if (!errors.value.length) {
    sending.value = true
    sendMail(title.value, recipient.value, aiResponse.value.replace(/\n/g, '<br>')).then(() => {
      useStore().setToast('Email sent.')
      setTimeout(() => {
        hideModal();
        sending.value = false
      }, 1000)
    })
  }
}

const checkForm = () => {
  errors.value = [];
  if (!isEmail(recipient.value)) {
    errors.value.push('Please enter a valid email address');
  }
  if (!(title.value)) {
    errors.value.push('Please enter a title');
  }
  if (!(aiResponse.value)) {
    errors.value.push('Please enter a message');
  }
}

</script>

<template>

  <div class="flex flex-row sm:ml-10 space-x-2 text-xs items-center hover:cursor-pointer" @click="showModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
         class="lucide lucide-send">
      <path d="m22 2-7 20-4-9-9-4Z"/>
      <path d="M22 2 11 13"/>
    </svg>
  </div>

  <Modal :show="isShowModal">

    <div class="p-4 w-full space-y-4 flex flex-col">
      <div class="text-lg">{{ $t('app.project.send.title') }}</div>
      <InputLabel :value="$t('app.project.send.recipient')"/>
      <TextInput v-model="recipient"></TextInput>
      <InputLabel :value="$t('app.project.send.subject')"/>
      <TextArea rows="1" class="w-full" v-model="title"></TextArea>
      <InputLabel :value="$t('app.project.send.content')"/>
      <TextArea v-model="aiResponse" rows="8" class="w-full"></TextArea>

      <div v-if="errors.length" class="alert alert-error mt-2 block">
        <ul>
          <li v-for="error in errors">{{ error }}</li>
        </ul>
      </div>

      <div class="flex flex-col space-y-2" v-if="!ai">
        <div class="flex flex-row justify-between alert alert-error">
          {{ $t('app.ai_not_available') }}
          <PrimaryButton @click="gotTo(route('subscribe.checkout'))">
            {{ $t('app.subscribe') }}
          </PrimaryButton>
        </div>
      </div>

      <div class="flex flex-row justify-between">
        <SecondaryButton @click="hideModal">{{ $t('app.cancel') }}</SecondaryButton>
        <div class="flex flex-row justify-between space-x-5">
          <PrimaryButton @click="send" :title="$t('app.project.send')" :disabled="loading || sending">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-send">
              <path d="m22 2-7 20-4-9-9-4Z"/>
              <path d="M22 2 11 13"/>
            </svg>
            <div class="ml-2">
              {{ $t('app.project.send.button_text') }}
            </div>
          </PrimaryButton>
        </div>
      </div>
    </div>

  </Modal>
</template>
