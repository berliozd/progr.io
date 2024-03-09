<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

import axios from "axios";
import {capitalize, ref} from "vue";
import {getActiveLanguage} from "laravel-vue-i18n";

const props = defineProps({
    projectTitle: null,
    projectDescription: null,
    projectNote: null,
    noteTypeCode: null,
    noteTypeLabel: null
})

const askAI = async () => {
    loading.value = true
    let context = props.projectTitle + ' - ' + props.projectDescription;
    if (typeof props.title === "object") {
        context = props.projectTitle.value + ' - ' + props.projectDescription.value;
    }
    let question = getQuestion();
    console.log(question);
    const response = await axios.post('/api/ai/', {'context': context, 'question': question})
    loading.value = false
    aiResponse.value = response.data.response
}

const getQuestion = () => {
    let question = getBaseQuestion();
    question += ', in your answer use bullets points';
    question += ', in your answer use carriage return';
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
    console.log(props.noteTypeCode);
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

let isShowModal = ref(false)
const showModal = () => {
    aiResponse.value = ''
    isShowModal.value = true
    askAI()
}
const hideModal = () => {
    isShowModal.value = false
}

const aiResponse = ref('');

const useForNote = () => {
    console.log('use for note');
    props.projectNote.content = aiResponse
    hideModal()
}
let loading = ref(false)
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
        <div class="p-4 w-full space-y-4 flex flex-col">
            <div class="w-full flex flex-row justify-between">
                <div class="text-gray-800 dark:text-gray-200">
                    {{ $t('app.project.note', {'label': capitalize(noteTypeLabel)}) }}
                </div>
                <div class="space-x-2 items-center">
                    <span class="loading loading-spinner loading-s bg-gray-400" v-show="loading"></span>
                    <PrimaryButton @click="askAI" v-bind:disabled="loading" class="disabled">
                        {{ $t('app.project.ask_ai') }}
                    </PrimaryButton>
                </div>

            </div>
            <TextArea v-model="aiResponse" rows="8" class="w-full"></TextArea>
            <div class="flex flex-row justify-between">
                <SecondaryButton @click="hideModal">{{ $t('app.cancel') }}</SecondaryButton>
                <SecondaryButton v-if="aiResponse" @click="useForNote">
                    {{ $t('app.project.use_for_note') }}
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
