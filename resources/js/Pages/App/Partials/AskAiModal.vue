<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

import axios from "axios";
import {capitalize, ref} from "vue";
import {getActiveLanguage, trans} from "laravel-vue-i18n";

const props = defineProps({
    projectTitle: null,
    projectDescription: null,
    projectNote: null,
    noteTypeCode: null,
    noteTypeLabel: null
})

const askAI = async () => {
    let context = props.projectTitle + ' - ' + props.projectDescription;
    if (typeof props.title === "object") {
        context = props.projectTitle.value + ' - ' + props.projectDescription.value;
    }
    let question = getQuestion();
    console.log(question);
    const response = await axios.post('/api/ai/', {'context': context, 'question': question})
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
</script>

<template>
    <img src="/img/ai.svg" v-bind:alt="trans('app.project.ask_ai_help')" width="30" @click="showModal"/>
    <Modal :show="isShowModal">
        <div class="p-4 w-full space-y-4 flex flex-col ">
            <div class="w-full flex flex-row justify-between">
                <div>{{ $t('app.project.note', {'label': capitalize(noteTypeLabel)}) }}</div>
                <PrimaryButton @click="askAI">{{ $t('app.project.ask_ai') }}</PrimaryButton>
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
